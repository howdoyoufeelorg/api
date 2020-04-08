<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/04/2020
 * Time: 1:10 pm
 */

namespace App\DataFixtures;


use App\Entity\FrontElement;
use App\Entity\FrontElementContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FrontElementFixtures extends Fixture
{
    private $elements = [
        'button_ok' => [
            'en' => 'OK',
            'es' => 'OK'
        ],
        'button_cancel' => [
            'en' => 'Cancel',
            'es' => 'Cancelar'
        ],
        'button_submit' => [
            'en' => 'Submit',
            'es' => 'Enviar'
        ],
        'button_yes' => [
            'en' => 'Yes',
            'es' => 'Si'
        ],
        'button_no' => [
            'en' => 'No',
            'es' => 'No'
        ],
        'button_close' => [
            'en' => 'Close',
            'es' => 'Cerca'
        ],
        'button_start' => [
            'en' => 'Start',
            'es' => '¡Comienzo!'
        ],
        'language_selector_search_placeholder' => [
            'en' => 'Search for Language',
            'es' => 'Búsqueda de idioma'
        ],
        'country_selector_search_placeholder' => [
            'en' => 'Search for Country',
            'es' => 'Búsqueda por país'
        ],
        'zipcode_input_placeholder' => [
            'en' => 'Zip code',
            'es' => 'Código postal'
        ],
        'app_title' => [
            'en' => 'How Do You Feel',
            'es' => '¿Cómo te sientes?'
        ],
        'dialog_disclaimer_title' => [
            'en' => 'Disclaimer',
            'es' => 'Descargo de responsabilidad'
        ],
        'dialog_disclaimer_content' => [
            'en' => 'Disclaimer',
            'es' => 'Descargo de responsabilidad'
        ],
        'dialog_emergency_title' => [
            'en' => 'Is this an Emergency?',
            'es' => '¿Es esto una emergencia?'
        ],
        'dialog_emergency_content' => [
            'en' => 'Is this an Emergency?',
            'es' => '¿Es esto una emergencia?'
        ],
        'dialog_survey_title' => [
            'en' => 'Survey',
            'es' => 'Encuesta'
        ],
        'dialog_survey_content' => [
            'en' => 'Survey',
            'es' => 'Encuesta'
        ],
        'dialog_instructions_title' => [
            'en' => 'Thank You',
            'es' => 'Gracias'
        ],
        'dialog_instructions_content' => [
            'en' => 'Instructions',
            'es' => 'Instrucciones'
        ],
        'dialog_call911_title' => [
            'en' => 'Call 911',
            'es' => 'Llama al 911'
        ],
        'dialog_call911_content' => [
            'en' => 'Call 911',
            'es' => 'Llama al 911'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach($this->elements as $elementId => $elementContents) {
            $frontElement = new FrontElement();
            $manager->persist($frontElement);
            $frontElement->setElementId($elementId);
            foreach($elementContents as $language => $content) {
                $frontElement->addContent(new FrontElementContent($language, $content));
            }
        }
        $manager->flush();
    }
}