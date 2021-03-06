<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 20/03/2020
 * Time: 5:46 pm
 */

namespace App\Controller;

use App\Entity\AbstractGeoEntity;
use App\Entity\Answer;
use App\Entity\FrontElement;
use App\Entity\Instruction;
use App\Entity\Question;
use App\Entity\Severity;
use App\Entity\Survey;
use App\Entity\Visitor;
use App\Entity\ZipcodePartial;
use App\Helper\CloudCache;
use App\Helper\Security;
use Doctrine\ORM\EntityManagerInterface;
use Google\Cloud\Translate\V3\TranslationServiceClient;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $tokenIssuer = 'https://howdoyoufeel.org';
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CloudCache
     */
    private $cache;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, Security $security, CloudCache $cache)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->security = $security;
        $this->cache = $cache;
    }

    /**
     * @Route("/test", name="test", methods={"GET", "POST"})
     */
    public function test()
    {
        return new JsonResponse(['test' => 'OK']);
    }

    /**
     * @Route("/get-hash", name="get_hash", methods={"GET"})
     */
    public function getHash()
    {
        $jtiId = '4f1g23a12aa';
        $uid = uniqid();
        $time = time();
        $token = (new Builder())->issuedBy($this->tokenIssuer) // Configures the issuer (iss claim)
                ->permittedFor($this->tokenIssuer) // Configures the audience (aud claim)
                ->identifiedBy($jtiId, true) // Configures the id (jti claim), replicating as a header item
                ->issuedAt($time) // Configures the time that the token was issue (iat claim)
                ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
                ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
                ->withClaim('uid', $uid) // Configures a new claim, called "uid"
                ->getToken(); // Retrieves the generated token

        return new JsonResponse([
            'hash' => (string)$token
        ]);
    }

    /**
     * @Route("/post-survey", name="post_survey", methods={"POST"})
     */
    public function postSurvey(Request $request)
    {
        $response = new JsonResponse();
        // Process the survey data here
        // Check for hash
        // Check if there's the user with this hash
        // If not, create new user, store survey
        // If found, just store survey
        $hash = $request->request->get('hash');
        $answers = $request->request->get('answers');
        $geolocation = $request->request->get('geolocation');
        $language = $request->request->get('language');
        $token = (new Parser())->parse((string) $hash);
        if($token->getClaim('iss') !== $this->tokenIssuer) {
            $response->setStatusCode(500);
        } else {
            $country = $answers['country']; unset($answers['country']);
            $zipcode = $answers['zipcode']; unset($answers['zipcode']);
            $age = $answers['age']['value'] ?? 0 ; unset($answers['age']);
            $gender = $answers['gender']['value'] ?? ''; unset($answers['gender']);
            $race = $answers['race']['value'] ?? ''; unset($answers['race']);
            //$language = $answers['language']; unset($answers['language']);
            $visitorUid = $token->getClaim('uid');
            $visitor = $this->entityManager->getRepository(Visitor::class)->findOneBy(['hash' => $visitorUid]);
            if(! $visitor instanceof Visitor) {
                $visitor = new Visitor($visitorUid);
                $this->entityManager->persist($visitor);
            }
            $visitor->setIp($request->getClientIp());
            $visitor->setLatitude($geolocation['latitude'] ? $geolocation['latitude'] : 0);
            $visitor->setLongitude($geolocation['longitude'] ? $geolocation['longitude'] : 0);
            $visitor->setAge($age);
            $visitor->setGender($gender);
            $visitor->setRace($race);
            // Process $answers
            $survey = new Survey();
            $this->entityManager->persist($survey);
            $survey->setLanguage($language);
            $survey->setCountry($country['value']);
            $survey->setZipcode($zipcode['value']);
            $sicknessIndex = 0;
            foreach($answers as $questionId => $surveyAnswer) {
                $question = $this->entityManager->getRepository(Question::class)->find($questionId);
                if($question instanceof Question) {
                    $answer = new Answer();
                    $answer->setQuestion($question);
                    $answer->setResponse($surveyAnswer['answer']);
                    if(array_key_exists('additionalData', $surveyAnswer)) $answer->setAdditionalData($surveyAnswer['additionalData']);
                    $this->entityManager->persist($answer);
                    $survey->addAnswer($answer);
                    $sicknessIndex += $this->calculateSicknessIndex($question, $surveyAnswer['answer']);
                }
            }
            $survey->setSicknessIndex($sicknessIndex);
            $visitor->addSurvey($survey);
            $this->entityManager->flush();
        }
        return $response;
    }

    /**
     * @Route("/get-elements", name="get_elements", methods={"GET"})
     */
    public function getElements()
    {
        $elements = [];
        $frontElements = $this->entityManager->getRepository(FrontElement::class)->findAll();
        foreach($frontElements as $frontElement) {
            $elements[$frontElement->getElementId()] = $frontElement->getContents()->toArray();
        }
        return new JsonResponse($elements);
    }

    /**
     * @Route("/get-questions", name="get_questions", methods={"GET"})
     */
    public function getQuestions()
    {
        // Try to get the questions from Redis cache
        $questions = $this->cache->getCache(CloudCache::CACHE_KEY_QUESTIONS);
        if(empty($questions) || !is_array($questions)) {
            $questions_db = $this->entityManager->getRepository(Question::class)->findEnabledQuestions();
            foreach ($questions_db as $q) {
                /** @var Question $q */
                $labels = [];
                foreach ($q->getLabels() as $label) {
                    $labels[$label->getLanguage()] = $label->getLabel();
                }
                $additionalDataLabels = [];
                foreach ($q->getAdditionalDataLabels() as $label) {
                    $additionalDataLabels[$label->getLanguage()] = $label->getLabel();
                }
                $questions[] = [
                    'id' => $q->getId(),
                    'questionNo' => $q->getQuestionNo(),
                    'questionWeight' => $q->getQuestionWeight(),
                    'type' => $q->getType(),
                    'labels' => $labels,
                    'required' => $q->getRequired(),
                    'requiresAdditionalData' => $q->getRequiresAdditionalData(),
                    'additionalDataType' => $q->getAdditionalDataType(),
                    'additionalDataLabels' => $additionalDataLabels,
                ];
            }
            $this->cache->setCache(CloudCache::CACHE_KEY_QUESTIONS, $questions);
        }
        return new JsonResponse([
            "questions" => $questions
        ]);
    }

    /**
     * @Route("/get-instructions", name="get_instructions", methods={"POST"})
     */
    public function getInstructions(Request $request)
    {
        $response = new JsonResponse();
        $hash = $request->request->get('hash');
        $token = (new Parser())->parse((string) $hash);
        if($token->getClaim('iss') !== $this->tokenIssuer) {
            $response->setStatusCode(500);
        } else {
            $data = [];
            $visitorUid = $token->getClaim('uid');
            $visitor = $this->entityManager->getRepository(Visitor::class)->findOneBy(['hash' => $visitorUid]);
            if($visitor instanceof Visitor) {
                $lastSurvey = $visitor->getLastSurvey();
                $zipcode = $lastSurvey->getZipcode();
                $severity = $this->determineSeverity($lastSurvey->getSicknessIndex());
                $data = $this->packInstructionsForZipcode($zipcode, $severity);
                $data['surveyId'] = $lastSurvey->getId();
                $data['severity'] = $severity;
            }
            $response->setData($data);
        }
        return $response;
    }

    /**
     * @Route("/test-instructions/{severity}/{zipcode}", name="test_instructions", methods={"GET"})
     */
    public function testInstructions($severity, $zipcode)
    {
        $response = new JsonResponse();
        $data = $this->packInstructionsForZipcode($zipcode, $severity);
        $response->setData($data);
        return $response;
    }

    private function packInstructionsForZipcode(string $zipcode, string $severity)
    {
        $cacheKey = $this->cache->constructInstructionsKey($zipcode, $severity);
        $data = $this->cache->getCache($cacheKey);
        if(empty($data) || !is_array($data)) {
            $geoEntities = $this->determineGeoEntities($zipcode);
            $instructions = $this->entityManager->getRepository(Instruction::class)->findInstructions($geoEntities, $severity);
            $packedInstructions = [];
            foreach ($instructions as $instruction) {
                /** @var Instruction $instruction */
                $contents = [];
                foreach ($instruction->getContents() as $instructionContent) {
                    $contents[$instructionContent->getLanguage()] = $instructionContent->getContent();
                }
                $geoentity = $this->determineInstructionGeoentityLevel($instruction);
                $packedInstructions[] = [
                    'createdBy' => $instruction->getCreatedBy()->getFullname(),
                    'createdAt' => $instruction->getCreatedAt()->format(DATE_ATOM),
                    'updatedAt' => $instruction->getUpdatedAt()->format(DATE_ATOM),
                    'contents' => $contents,
                    'geoentity' => $geoentity
                ];
            }
            $data['instructions'] = $packedInstructions;
            $data['resources'] = $this->determineResources($geoEntities);
            $this->cache->setCache($cacheKey, $data, 3600);
        }
        return $data;
    }

    private function calculateSicknessIndex(Question $question, string $answer)
    {
        $score = 0;
        if(is_numeric($answer)) {
            $numericAnswer = (int)$answer;
            $score = floor($question->getQuestionWeight() / $numericAnswer);
        }
        if(strtolower($answer) == 'no') {
            $score = $question->getQuestionWeight() / 10;
        }
        if(strtolower($answer) == 'yes') {
            $score = $question->getQuestionWeight();
        }
        return $score;
    }

    private function determineSeverity($sicknessIndex)
    {
        // Fill range is 45 to 450, where 45 is not sick at all, and 450 is very sick
        if($sicknessIndex < 100) {
            return Severity::LOW;
        }
        if($sicknessIndex > 200) {
            return Severity::HIGH;
        }
        return Severity::MEDIUM;
    }

    private function determineGeoEntities(string $zipcode) : array
    {
        $geoentities = [];
        for ($i = 3; $i > 0; $i--) {
            $partial = substr($zipcode, 0, $i);
            $zipcodePartials = $this->entityManager->getRepository(ZipcodePartial::class)->findBy(['partial' => $partial]);
            if ($zipcodePartials) {
                /** @var ZipcodePartial $firstMatchingPartial */
                $firstMatchingPartial = current($zipcodePartials);
                $geoentities = [
                    'area' => $firstMatchingPartial->getArea(),
                    'state' => $firstMatchingPartial->getArea()->getState(),
                    'country' => $firstMatchingPartial->getArea()->getState()->getCountry(),
                    'zipcode' => $zipcode
                ];
            }
        }
        return $geoentities;
    }

    private function determineInstructionGeoentityLevel(Instruction $instruction) : string
    {
        if($instruction->getZipcode()) return 'zipcode';
        if($instruction->getArea()) return 'area';
        if($instruction->getState()) return 'state';
        if($instruction->getCountry()) return 'country';
        return '';
    }

    private function determineResources($geoEntities)
    {
        $resources = [];
        if(array_key_exists('country', $geoEntities)) {
            /** @var AbstractGeoEntity $entity */
            $entity = $geoEntities['country'];
            $resources['country'] = [
                'webResources' => $entity->getWebResources(),
                'twitterResources' => $entity->getTwitterResources(),
                'officialWebResources' => $entity->getOfficialWebResources(),
                'phoneNumbers' => $entity->getPhoneNumbers(),
            ];
        }
        if(array_key_exists('state', $geoEntities)) {
            /** @var AbstractGeoEntity $entity */
            $entity = $geoEntities['state'];
            $resources['state'] = [
                'webResources' => $entity->getWebResources(),
                'twitterResources' => $entity->getTwitterResources(),
                'officialWebResources' => $entity->getOfficialWebResources(),
                'phoneNumbers' => $entity->getPhoneNumbers(),
            ];
        }
        if(array_key_exists('area', $geoEntities)) {
            /** @var AbstractGeoEntity $entity */
            $entity = $geoEntities['area'];
            $resources['area'] = [
                'webResources' => $entity->getWebResources(),
                'twitterResources' => $entity->getTwitterResources(),
                'officialWebResources' => $entity->getOfficialWebResources(),
                'phoneNumbers' => $entity->getPhoneNumbers(),
            ];
        }
        return $resources;
    }

    /**
     * @Route("/do-translate")
     */
    public function doTranslate(Request $request)
    {
        $language = $request->request->get('language');
        $text = $request->request->get('content');

        $translationClient = new TranslationServiceClient();
        $content = [$text];
        $response = $translationClient->translateText(
            $content,
            $language,
            TranslationServiceClient::locationName('m-app-3', 'global')
        );
        $translations = $response->getTranslations();
        $firstTranslation = $translations->offsetGet(0);
        $translatedText = $firstTranslation->getTranslatedText();

        return new JsonResponse([
            'language' => $language,
            'translation' => $translatedText
        ]);
    }
}