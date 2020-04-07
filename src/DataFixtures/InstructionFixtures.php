<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Instruction;
use App\Entity\InstructionContent;
use App\Entity\Severity;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InstructionFixtures extends Fixture implements DependentFixtureInterface
{
    private $languages = [
        'en',
        'es',
        'fr',
        'de'
    ];

    private $zipcodeInstructions = [
        ['Zipcode Instruction 1 NORMAL', Severity::NORMAL, '78620'],
        ['Zipcode Instruction 2 NORMAL', Severity::NORMAL, '78620'],
        ['Zipcode Instruction 1 HIGH',   Severity::HIGH,   '78620'],
        ['Zipcode Instruction 2 HIGH',   Severity::HIGH,   '78620'],
        ['Zipcode Instruction 1 LOW',    Severity::LOW,    '78620'],
        ['Zipcode Instruction 2 LOW',    Severity::LOW,    '78620'],
        ['Zipcode Instruction 1 NORMAL', Severity::NORMAL, '48864'],
        ['Zipcode Instruction 2 NORMAL', Severity::NORMAL, '48864'],
        ['Zipcode Instruction 1 HIGH',   Severity::HIGH,   '48864'],
        ['Zipcode Instruction 2 HIGH',   Severity::HIGH,   '48864'],
        ['Zipcode Instruction 1 LOW',    Severity::LOW,    '48864'],
        ['Zipcode Instruction 2 LOW',    Severity::LOW,    '48864'],
    ];

    private $areaInstructions = [
        ['Area Instruction 1 NORMAL', Severity::NORMAL, 'Texas 78'],
        ['Area Instruction 2 NORMAL', Severity::NORMAL, 'Texas 78'],
        ['Area Instruction 1 HIGH',   Severity::HIGH,   'Texas 78'],
        ['Area Instruction 2 HIGH',   Severity::HIGH,   'Texas 78'],
        ['Area Instruction 1 LOW',    Severity::LOW,    'Texas 78'],
        ['Area Instruction 2 LOW',    Severity::LOW,    'Texas 78'],
        ['Area Instruction 1 NORMAL', Severity::NORMAL, 'Michigan 48'],
        ['Area Instruction 2 NORMAL', Severity::NORMAL, 'Michigan 48'],
        ['Area Instruction 1 HIGH',   Severity::HIGH,   'Michigan 48'],
        ['Area Instruction 2 HIGH',   Severity::HIGH,   'Michigan 48'],
        ['Area Instruction 1 LOW',    Severity::LOW,    'Michigan 48'],
        ['Area Instruction 2 LOW',    Severity::LOW,    'Michigan 48'],
    ];

    private $stateInstructions = [
        ['State Instruction 1 NORMAL', Severity::NORMAL, 'Texas'],
        ['State Instruction 2 NORMAL', Severity::NORMAL, 'Texas'],
        ['State Instruction 1 HIGH',   Severity::HIGH,   'Texas'],
        ['State Instruction 2 HIGH',   Severity::HIGH,   'Texas'],
        ['State Instruction 1 LOW',    Severity::LOW,    'Texas'],
        ['State Instruction 2 LOW',    Severity::LOW,    'Texas'],
        ['State Instruction 1 NORMAL', Severity::NORMAL, 'Michigan'],
        ['State Instruction 2 NORMAL', Severity::NORMAL, 'Michigan'],
        ['State Instruction 1 HIGH',   Severity::HIGH,   'Michigan'],
        ['State Instruction 2 HIGH',   Severity::HIGH,   'Michigan'],
        ['State Instruction 1 LOW',    Severity::LOW,    'Michigan'],
        ['State Instruction 2 LOW',    Severity::LOW,    'Michigan'],
    ];
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
    }

    public function load(ObjectManager $manager)
    {
        // The token below was created manually and copied here - no other quick solution at the time (oAuth)
        $token = '1//09RHb6wjKoireCgYIARAAGAkSNwF-L9IrBEXWSR-6BpSNBpMXMvC8yhCy1NV9wxCEJVxdZIUCUBFGyktpFTInNugEJw1aKjbdDhk';
        $client_id = $this->parameterBag->get('google_client_id');
        $client_secret = $this->parameterBag->get('google_client_secret');
        $client = new \Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->refreshToken($token);
        $translate = new \Google_Service_Translate($client);

        $editor = $this->getReference(UserFixtures::EDITOR_USER_REFERENCE);

        foreach($this->zipcodeInstructions as $instructionData) {
            $text = $instructionData[0]; $severity = $instructionData[1]; $zipcode = $instructionData[2];
            $instruction = new Instruction();
            $instruction->setZipcode($zipcode);
            $instruction->setSeverity($severity);
            $instruction->setCreatedBy($editor);
            $content = "$zipcode - $text";
            foreach($this->languages as $language) {
                if($language != 'en') {
                    $content = $this->getTranslation($translate, $language, $content);
                }
                $instruction->addContent( new InstructionContent($language, $content));
            }
            $manager->persist($instruction);
        }
        foreach($this->areaInstructions as $instructionData) {
            $text = $instructionData[0]; $severity = $instructionData[1]; $areaName = $instructionData[2];
            $area = $manager->getRepository(Area::class)->findOneBy(['name' => $areaName]);
            if($area instanceof Area) {
                $instruction = new Instruction();
                $instruction->setArea($area);
                $instruction->setSeverity($severity);
                $instruction->setCreatedBy($editor);
                $content = "$zipcode - $text";
                foreach($this->languages as $language) {
                    if($language != 'en') {
                        $content = $this->getTranslation($translate, $language, $content);
                    }
                    $instruction->addContent( new InstructionContent($language, $content));
                }
                $manager->persist($instruction);
            }
        }
        foreach($this->stateInstructions as $instructionData) {
            $text = $instructionData[0]; $severity = $instructionData[1]; $stateName = $instructionData[2];
            $state = $manager->getRepository(State::class)->findOneBy(['name' => $stateName]);
            if($state instanceof State) {
                $instruction = new Instruction();
                $instruction->setState($state);
                $instruction->setSeverity($severity);
                $instruction->setCreatedBy($editor);
                $content = "$zipcode - $text";
                foreach($this->languages as $language) {
                    if($language != 'en') {
                        $content = $this->getTranslation($translate, $language, $content);
                    }
                    $instruction->addContent( new InstructionContent($language, $content));
                }
                $manager->persist($instruction);
            }
        }
        $manager->flush();
    }

    private function getTranslation(\Google_Service_Translate $client, string $language, string $text)
    {
        $request = new \Google_Service_Translate_TranslateTextRequest();
        $request->setTargetLanguageCode($language);
        $request->setContents($text);
        $result = $client->projects->translateText('projects/m-app-3', $request);
        $translations = $result->getTranslations();
        $firstTranslation = $translations[0];
        /** @var \Google_Service_Translate_Translation $firstTranslation */
        $translatedText = $firstTranslation->getTranslatedText();
        return $translatedText;
    }

    public function getDependencies()
    {
        return [
            UsGeoEntitiesFixtures::class
        ];
    }
}
