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
                        'label' => 'En una escala de 1 a 10'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Esta teniendo problemas para respirar?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Tiene fiebre?'
                    ],
                    [
                        'language' => 'rs',
                        'label' => 'Imate li povisenu temperaturu?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => true,
                'additionalDataType' => Question::TYPE_ENTRY,
                'additionalDataLabels' => [
                    [
                        'language' => 'en',
                        'label' => 'What is Your Temperature?'
                    ],
                    [
                        'language' => 'es',
                        'label' => '¿cuál es su temperatura?'
                    ],
                    [
                        'language' => 'rs',
                        'label' => 'Kolika Vam je temperatura?'
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
                        'label' => '¿Tiene 55 años de edad o más?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Usted ha estado en contacto cercano con personas con síntomas o que tienen el virus?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Usted ha viajado recientemente?'
                    ],
                    [
                        'language' => 'rs',
                        'label' => 'Jeste li skorije putovali?'
                    ]
                ],
                'required' => true,
                'requiresAdditionalData' => true,
                'additionalDataType' => Question::TYPE_ENTRY,
                'additionalDataLabels' => [
                    [
                        'language' => 'en',
                        'label' => 'Where Did You Go?'
                    ],
                    [
                        'language' => 'es',
                        'label' => '¿a dónde viajo?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Tiene una condición de salud existente adicional?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Está experimentando pérdida de sabor u olor?'
                    ],
                    [
                        'language' => 'rs',
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
                        'label' => '¿Tiene tos?'
                    ],
                    [
                        'language' => 'rs',
                        'label' => 'Kašljete li?'
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
