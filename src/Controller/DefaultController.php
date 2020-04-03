<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 20/03/2020
 * Time: 5:46 pm
 */

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route as Route;

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

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
        $data = json_decode($request->getContent(), true);
        // Process the survey data here
        // Check for hash
        // Check if there's the user with this hash
        // If not, create new user, store survey
        // If found, just store survey
        $hash = $data['hash'];
        $answers = $data['answers'];
        $geolocation = $data['geolocation'];
        $token = (new Parser())->parse((string) $hash);
        if($token->getClaim('iss') !== $this->tokenIssuer) {
            $response->setStatusCode(500);
        } else {
            $country = $answers['country']; unset($answers['country']);
            $zipcode = $answers['zipcode']; unset($answers['zipcode']);
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
            // Process $answers
            $survey = new Survey();
            $this->entityManager->persist($survey);
            $survey->setLanguage('en-US');
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
     * @Route("/get-questions", name="get_questions", methods={"GET"})
     */
    public function getQuestions()
    {
        $questions = [];
        $questions_db = $this->entityManager->getRepository(Question::class)->findEnabledQuestions();
        foreach($questions_db as $q) {
            /** @var Question $q */
            $labels = [];
            foreach($q->getLabels() as $label) {
                $labels[$label->getLanguage()] = $label->getLabel();
            }
            $additionalDataLabels = [];
            foreach($q->getAdditionalDataLabels() as $label) {
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
        return new JsonResponse([
            "questions" => $questions
        ]);
    }

    /**
     * @Route("/get-instructions", name="get_instructions", methods={"GET"})
     */
    public function getInstructions()
    {
        return new JsonResponse();
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
}