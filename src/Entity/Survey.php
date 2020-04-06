<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 18/03/2020
 * Time: 5:37 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 * @ORM\Table(name="surveys")
 * @ApiResource(
 *     normalizationContext={"groups"={"surveys_read"}},
 *     denormalizationContext={"groups"={"surveys_write"}}
 * )
 */
class Survey
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hash;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Visitor", inversedBy="surveys")
     */
    private $visitor;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipcode;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="survey")
     */
    private $answers;
    /**
     * @ORM\Column(type="integer")
     */
    private $sicknessIndex;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Survey
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return Survey
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param mixed $visitor
     * @return Survey
     */
    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     * @return Survey
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Survey
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return Survey
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer $answers
     * @return Survey
     */
    public function addAnswer(Answer $answer)
    {
        $answer->setSurvey($this);
        $this->answers[] = $answer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSicknessIndex()
    {
        return $this->sicknessIndex;
    }

    /**
     * @param mixed $sicknessIndex
     * @return Survey
     */
    public function setSicknessIndex($sicknessIndex)
    {
        $this->sicknessIndex = $sicknessIndex;
        return $this;
    }
}