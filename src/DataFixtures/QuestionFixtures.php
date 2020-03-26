<?php

namespace App\DataFixtures;

use App\Entity\AdditionalDataLabel;
use App\Entity\Question;
use App\Entity\QuestionLabel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $questions = [
            [
                'weight' => 90,
                'type' => Question::TYPE_SLIDER,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'On a Scale of 1 to 10'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Na skali od 1 do 10'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,

            ],
            [
                'weight' => 80,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Are You Having Trouble Breathing?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Imate li poteskoca u disanju'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
            [
                'weight' => 70,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Do You Have a Fever? '
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Imate li povisenu temperaturu?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => true,
                'additionalDataType' => Question::TYPE_ENTRY,
                'additionalDataLabels' => [
                    [
                        'language' => 'en',
                        'label' => 'Temperature?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Temperatura?'
                    ]
                ]
            ],
            [
                'weight' => 60,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Are You 55 years of age or older?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Da li ste stariji od 55 godina?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
            [
                'weight' => 50,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Have You Been in Close Contact with Others with Symptoms or that have the Virus?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Da li ste bili u bliskom kontaktu sa zarazenim?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
            [
                'weight' => 40,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Have You Travelled Recently?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Jeste li skorije putovali?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => true,
                'additionalDataType' => Question::TYPE_ENTRY,
                'additionalDataLabels' => [
                    [
                        'language' => 'en',
                        'label' => 'Where?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Gde?'
                    ]
                ]
            ],
            [
                'weight' => 30,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Do You Have an Additional Existing Health Condition?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Da li imate hronicne zdravstvene probleme?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
            [
                'weight' => 20,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Are You Experiencing Loss of Taste or Smell?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Da li ste izgubili culo mirisa?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
            [
                'weight' => 10,
                'type' => Question::TYPE_YESNO,
                'labels' => [
                    [
                        'language' => 'en',
                        'label' => 'Do You Have a Cough?'
                    ],
                    [
                        'language' => 'es',
                        'label' => 'Kasljete li?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => false,
            ],
        ];

        foreach($questions as $q) {
            $question = new Question;
            $manager->persist($question);
            $question->setQuestionWeight($q['weight']);
            $question->setType($q['type']);
            foreach($q['labels'] as $l) {
                $label = new QuestionLabel();
                $manager->persist($label);
                $label->setLanguage($l['language']);
                $label->setLabel($l['label']);
                $question->addLabel($label);
            }
            $question->setRequired($q['required']);
            if($q['requiresAdditionalData']) {
                $question->setRequiresAdditionalData(true);
                $question->setAdditionalDataType($q['additionalDataType']);
                foreach($q['additionalDataLabels'] as $l) {
                    $label = new AdditionalDataLabel();
                    $manager->persist($label);
                    $label->setLanguage($l['language']);
                    $label->setLabel($l['label']);
                    $question->addAdditionalDataLabel($label);
                }
            }
        }
        $manager->flush();
    }
}
