<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 25/03/2020
 * Time: 12:11 pm
 */

namespace App\Command;


use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Survey;
use App\Entity\Visitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SurveyWriteTest extends Command
{
    public static $defaultName = 'api:survey_write_test';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $answers = [
            1 => 7,
            2 => 'NO', 3 => 'NO', 4 => 'NO', 5 => 'NO', 6 => 'NO', 7 => 'NO', 8 => 'NO', 9 => 'NO', 10 => 'YES',
        ];

        $country = 'US';
        $zipcode = '48864';
        $language = 'en-US';
        $visitorUid = '1234'; //$token->getClaim('uid');
        $visitor = $this->entityManager->getRepository(Visitor::class)->findOneBy(['hash' => $visitorUid]);
        if(! $visitor instanceof Visitor) {
            $visitor = new Visitor($visitorUid);
            $visitor->setIp('127.0.0.1');
            $visitor->setLatitude(0);
            $visitor->setLongitude(0);
            $this->entityManager->persist($visitor);
        }
        // Process $answers
        $survey = new Survey();
        $this->entityManager->persist($survey);
        $survey->setLanguage($language);
        $survey->setCountry($country);
        $survey->setZipcode($zipcode);
        foreach($answers as $questionId => $surveyAnswer) {
            $question = $this->entityManager->getRepository(Question::class)->find($questionId);
            if($question instanceof Question) {
                $answer = new Answer();
                $answer->setQuestion($question);
                $answer->setResponse($surveyAnswer);
                $this->entityManager->persist($answer);
                $survey->addAnswer($answer);
            }
        }
        $survey->setWellnessIndex(111);
        $visitor->addSurvey($survey);
        $this->entityManager->flush();
        $output->writeln('DONE');
    }


}