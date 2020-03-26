<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 12:01 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="areas")
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 */
class Area
{
    use TimestampableEntity;

    const DEFINED_BY_ZIPCODE = 'zipcode';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $label;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $definedBy;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instruction", mappedBy="area")
     */
    private $instructions;

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
     * @return Area
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return Area
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefinedBy()
    {
        return $this->definedBy;
    }

    /**
     * @param mixed $definedBy
     * @return Area
     */
    public function setDefinedBy($definedBy)
    {
        $this->definedBy = $definedBy;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param mixed $instructions
     * @return Area
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
        return $this;
    }
}