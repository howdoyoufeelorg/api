<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 12:00 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;


/**
 * @ORM\Entity(repositoryClass="App\Repository\InstructionRepository")
 * @ORM\Table(name="instructions")
 * @ApiResource(
 *     normalizationContext={"groups"={"instructions_read"}},
 *     denormalizationContext={"groups"={"instructions_write"}}
 * )
 * @ApiFilter(OrderFilter::class, properties={"id", "country", "state", "area", "zipcode"}, arguments={"orderParameterName"="order"})
 * @Gedmo\Loggable
 */
class Instruction
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="instructions")
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $country;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="instructions")
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $state;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="instructions")
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $area;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $zipcode;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $severity;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InstructionContent", mappedBy="instruction", cascade={"persist"}, orphanRemoval=true)
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $contents;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instructions")
     */
    private $createdBy;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

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
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param mixed $zipcode
     * @return Instruction
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
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
     * @return InstructionContent[]|ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param array $contents
     * @return Instruction
     */
    public function setContents(array $contents)
    {
        foreach($contents as $content) {
            $content->setInstruction($this);
        }
        $this->contents = $contents;
        return $this;
    }

    /**
     * @param InstructionContent $content
     * @return Instruction
     */
    public function addContent(InstructionContent $content)
    {
        $content->setInstruction($this);
        $this->contents[] = $content;
        return $this;
    }

    /**
     * @return User
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