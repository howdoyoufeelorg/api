<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 11:55 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="answers")
 * @ApiResource(
 *     normalizationContext={"groups"={"answers_read"}},
 *     denormalizationContext={"groups"={"answers_write"}}
 * )
 */
class Answer
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="answers")
     */
    private $survey;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     */
    private $question;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $response;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalData;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Answer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     * @return Answer
     */
    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     * @return Answer
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return Answer
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param mixed $additionalData
     * @return Answer
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
        return $this;
    }
}