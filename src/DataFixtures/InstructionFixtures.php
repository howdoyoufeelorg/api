<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Instruction;
use App\Entity\Severity;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InstructionFixtures extends Fixture implements DependentFixtureInterface
{
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

    public function load(ObjectManager $manager)
    {
        $editor = $this->getReference(UserFixtures::EDITOR_USER_REFERENCE);

        foreach($this->zipcodeInstructions as $instructionData) {
            $text = $instructionData[0]; $severity = $instructionData[1]; $zipcode = $instructionData[2];
            $instruction = new Instruction();
            $instruction->setZipcode($zipcode);
            $instruction->setSeverity($severity);
            $instruction->setContents("$zipcode - $text");
            $instruction->setCreatedBy($editor);
            $manager->persist($instruction);
        }
        foreach($this->areaInstructions as $instructionData) {
            $text = $instructionData[0]; $severity = $instructionData[1]; $areaName = $instructionData[2];
            $area = $manager->getRepository(Area::class)->findOneBy(['name' => $areaName]);
            if($area instanceof Area) {
                $instruction = new Instruction();
                $instruction->setArea($area);
                $instruction->setSeverity($severity);
                $instruction->setContents("$areaName - $text");
                $instruction->setCreatedBy($editor);
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
                $instruction->setContents("$stateName - $text");
                $instruction->setCreatedBy($editor);
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
