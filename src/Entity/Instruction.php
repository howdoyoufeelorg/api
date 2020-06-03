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
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use App\Annotation\UserAware;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstructionRepository")
 * @ORM\Table(name="instructions")
 * @ApiResource(
 *     normalizationContext={"groups"={"instructions_read"}},
 *     denormalizationContext={"groups"={"instructions_write"}, "enable_max_depth"=true}
 * )
 * @ApiFilter(OrderFilter::class, properties={"id", "country", "state", "area", "zipcode", "createdAt", "updatedAt"}, arguments={"orderParameterName"="order"})
 * @Gedmo\Loggable
 * @UserAware(userFieldName="created_by_id")
 */
class Instruction
{
    //use TimestampableEntity;

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
     * @ORM\OneToMany(targetEntity="App\Entity\InstructionContent", mappedBy="instruction", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups({"instructions_read", "instructions_write"})
     * @MaxDepth(3)
     */
    private $contents;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instructions")
     */
    private $createdBy;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"instructions_read"})
     */
    protected $createdAt;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"instructions_read"})
     */
    protected $updatedAt;


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
     * @param InstructionContent $content
     * @return Instruction
     */
    public function addContent(InstructionContent $content)
    {
        if(! $this->contents->contains($content)) {
            $content->setInstruction($this);
            $this->contents[] = $content;
        }
        return $this;
    }

    public function removeContent(InstructionContent $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            // set the owning side to null (unless already changed)
            if ($content->getInstruction() === $this) {
                $content->setInstruction(null);
            }
        }
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}