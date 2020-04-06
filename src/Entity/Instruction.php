<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 12:00 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="instructions")
 * @ApiResource(
 *     normalizationContext={"groups"={"instructions_read"}},
 *     denormalizationContext={"groups"={"instructions_write"}}
 * )
 * @Gedmo\Loggable
 */
class Instruction
{
    use TimestampableEntity;

    const SEVERITY_LOW = 'low';
    const SEVERITY_NORMAL = 'normal';
    const SEVERITY_HIGH = 'high';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="instructions")
     */
    private $country;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="instructions")
     */
    private $state;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="instructions")
     */
    private $area;
    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $severity;
    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $contents;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instructions")
     */
    private $createdBy;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return Instruction
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Instruction
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     * @return Instruction
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param mixed $severity
     * @return Instruction
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     * @return Instruction
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     * @return Instruction
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }
}