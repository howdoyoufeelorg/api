<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 11:06 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="visitors")
 * @ApiResource(
 *     normalizationContext={"groups"={"visitors_read"}},
 *     denormalizationContext={"groups"={"visitors_write"}}
 * )
 */
class Visitor
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;
    /**
     * @ORM\Column(type="float")
     */
    private $latitude;
    /**
     * @ORM\Column(type="float")
     */
    private $longitude;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Survey", mappedBy="visitor")
     */
    private $surveys;
    /**
     * @ORM\Column(type="integer")
     */
    private $age;
    /**
     * @ORM\Column(type="string")
     */
    private $gender;
    /**
     * @ORM\Column(type="string")
     */
    private $race;

    public function __construct($hash)
    {
        $this->hash = $hash;
        $this->surveys = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Visitor
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
     * @return Visitor
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Visitor
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Visitor
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return Visitor
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return Survey[]|ArrayCollection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * @return Survey
     */
    public function getLastSurvey() {
        return $this->surveys->last();
    }

    /**
     * @param mixed $survey
     * @return Visitor
     */
    public function addSurvey(Survey $survey)
    {
        $survey->setVisitor($this);
        $this->surveys[] = $survey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     * @return Visitor
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Visitor
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * @param mixed $race
     * @return Visitor
     */
    public function setRace($race)
    {
        $this->race = $race;
        return $this;
    }
}