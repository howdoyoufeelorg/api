<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Instruction;
use App\Entity\InstructionContent;
use App\Entity\Severity;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InstructionFixtures extends Fixture implements DependentFixtureInterface
{
    private $languages = [
        'en',
        'es',
    ];

    private $zipcodeInstructions = [
        ['Zipcode Instruction 1 MEDIUM', 'Código postal instrucción 1 MEDIUM', Severity::MEDIUM, '78620'],
        ['Zipcode Instruction 2 MEDIUM', 'Código postal instrucción 2 MEDIUM', Severity::MEDIUM, '78620'],
        ['Zipcode Instruction 1 HIGH',   'Código postal instrucción 1 HIGH',   Severity::HIGH,   '78620'],
        ['Zipcode Instruction 2 HIGH',   'Código postal instrucción 2 HIGH',   Severity::HIGH,   '78620'],
        ['Zipcode Instruction 1 LOW',    'Código postal instrucción 1 LOW',    Severity::LOW,    '78620'],
        ['Zipcode Instruction 2 LOW',    'Código postal instrucción 2 LOW',    Severity::LOW,    '78620'],
        ['Zipcode Instruction 1 MEDIUM', 'Código postal instrucción 1 MEDIUM', Severity::MEDIUM, '48864'],
        ['Zipcode Instruction 2 MEDIUM', 'Código postal instrucción 2 MEDIUM', Severity::MEDIUM, '48864'],
        ['Zipcode Instruction 1 HIGH',   'Código postal instrucción 1 HIGH',   Severity::HIGH,   '48864'],
        ['Zipcode Instruction 2 HIGH',   'Código postal instrucción 2 HIGH',   Severity::HIGH,   '48864'],
        ['Zipcode Instruction 1 LOW',    'Código postal instrucción 1 LOW',    Severity::LOW,    '48864'],
        ['Zipcode Instruction 2 LOW',    'Código postal instrucción 2 LOW',    Severity::LOW,    '48864'],
    ];

    private $areaInstructions = [
        ['Area Instruction 1 MEDIUM', 'Instrucción de área 1 MEDIUM', Severity::MEDIUM, 'Texas 78'],
        ['Area Instruction 2 MEDIUM', 'Instrucción de área 2 MEDIUM', Severity::MEDIUM, 'Texas 78'],
        ['Area Instruction 1 HIGH',   'Instrucción de área 1 HIGH',   Severity::HIGH,   'Texas 78'],
        ['Area Instruction 2 HIGH',   'Instrucción de área 2 HIGH',   Severity::HIGH,   'Texas 78'],
        ['Area Instruction 1 LOW',    'Instrucción de área 1 LOW',    Severity::LOW,    'Texas 78'],
        ['Area Instruction 2 LOW',    'Instrucción de área 2 LOW',    Severity::LOW,    'Texas 78'],
        ['Area Instruction 1 MEDIUM', 'Instrucción de área 1 MEDIUM', Severity::MEDIUM, 'Michigan 48'],
        ['Area Instruction 2 MEDIUM', 'Instrucción de área 2 MEDIUM', Severity::MEDIUM, 'Michigan 48'],
        ['Area Instruction 1 HIGH',   'Instrucción de área 1 HIGH',   Severity::HIGH,   'Michigan 48'],
        ['Area Instruction 2 HIGH',   'Instrucción de área 2 HIGH',   Severity::HIGH,   'Michigan 48'],
        ['Area Instruction 1 LOW',    'Instrucción de área 1 LOW',    Severity::LOW,    'Michigan 48'],
        ['Area Instruction 2 LOW',    'Instrucción de área 2 LOW',    Severity::LOW,    'Michigan 48'],
    ];

    private $stateInstructions = [
        ['State Instruction 1 MEDIUM', 'Instrucción estatal 1 MEDIUM', Severity::MEDIUM, 'Texas'],
        ['State Instruction 2 MEDIUM', 'Instrucción estatal 2 MEDIUM', Severity::MEDIUM, 'Texas'],
        ['State Instruction 1 HIGH',   'Instrucción estatal 1 HIGH',   Severity::HIGH,   'Texas'],
        ['State Instruction 2 HIGH',   'Instrucción estatal 2 HIGH',   Severity::HIGH,   'Texas'],
        ['State Instruction 1 LOW',    'Instrucción estatal 1 LOW',    Severity::LOW,    'Texas'],
        ['State Instruction 2 LOW',    'Instrucción estatal 2 LOW',    Severity::LOW,    'Texas'],
        ['State Instruction 1 MEDIUM', 'Instrucción estatal 1 MEDIUM', Severity::MEDIUM, 'Michigan'],
        ['State Instruction 2 MEDIUM', 'Instrucción estatal 2 MEDIUM', Severity::MEDIUM, 'Michigan'],
        ['State Instruction 1 HIGH',   'Instrucción estatal 1 HIGH',   Severity::HIGH,   'Michigan'],
        ['State Instruction 2 HIGH',   'Instrucción estatal 2 HIGH',   Severity::HIGH,   'Michigan'],
        ['State Instruction 1 LOW',    'Instrucción estatal 1 LOW',    Severity::LOW,    'Michigan'],
        ['State Instruction 2 LOW',    'Instrucción estatal 2 LOW',    Severity::LOW,    'Michigan'],
    ];

    public function load(ObjectManager $manager)
    {
        $editor = $this->getReference(UserFixtures::EDITOR_USER_REFERENCE);

        foreach($this->zipcodeInstructions as $instructionData) {
            $textEN = $instructionData[0]; $textES = $instructionData[1]; $severity = $instructionData[2]; $zipcode = $instructionData[3];
            $instruction = new Instruction();
            $instruction->setZipcode($zipcode);
            $instruction->setSeverity($severity);
            $instruction->setCreatedBy($editor);
            foreach($this->languages as $language) {
                if($language != 'en') {
                    $content = "$zipcode - $textES";
                } else {
                    $content = "$zipcode - $textEN";
                }
                $instruction->addContent( new InstructionContent($language, $content));
            }
            $manager->persist($instruction);
        }
        foreach($this->areaInstructions as $instructionData) {
            $textEN = $instructionData[0]; $textES = $instructionData[1]; $severity = $instructionData[2]; $areaName = $instructionData[3];
            $area = $manager->getRepository(Area::class)->findOneBy(['name' => $areaName]);
            if($area instanceof Area) {
                $instruction = new Instruction();
                $instruction->setArea($area);
                $instruction->setSeverity($severity);
                $instruction->setCreatedBy($editor);
                foreach($this->languages as $language) {
                    if($language != 'en') {
                        $content = "$zipcode - $textES";
                    } else {
                        $content = "$zipcode - $textEN";
                    }
                    $instruction->addContent( new InstructionContent($language, $content));
                }
                $manager->persist($instruction);
            }
        }
        foreach($this->stateInstructions as $instructionData) {
            $textEN = $instructionData[0]; $textES = $instructionData[1]; $severity = $instructionData[2]; $stateName = $instructionData[3];
            $state = $manager->getRepository(State::class)->findOneBy(['name' => $stateName]);
            if($state instanceof State) {
                $instruction = new Instruction();
                $instruction->setState($state);
                $instruction->setSeverity($severity);
                $instruction->setCreatedBy($editor);
                foreach($this->languages as $language) {
                    if($language != 'en') {
                        $content = "$zipcode - $textES";
                    } else {
                        $content = "$zipcode - $textEN";
                    }
                    $instruction->addContent( new InstructionContent($language, $content));
                }
                $manager->persist($instruction);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsGeoEntitiesFixtures::class
        ];
    }
}
