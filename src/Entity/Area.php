<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 12:01 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="areas")
 * @ApiResource(
 *     normalizationContext={"groups"={"areas_read", "geoentity_read"}},
 *     denormalizationContext={"groups"={"areas_write"}}
 * )
 */
class Area extends AbstractGeoEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="areas")
     */
    private $state;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ZipcodePartial", mappedBy="area", cascade={"persist"}, orphanRemoval=true)
     */
    private $zipcodePartials;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instruction", mappedBy="area")
     */
    private $instructions;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="areas")
     */
    private $users;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->zipcodePartials = new ArrayCollection();
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Area
     */
    public function setState(State $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipcodePartials()
    {
        return $this->zipcodePartials;
    }

    /**
     * @param mixed $zipcodePartial
     * @return Area
     */
    public function addZipcodePartial(ZipcodePartial $zipcodePartial)
    {
        $zipcodePartial->setArea($this);
        $this->zipcodePartials->add($zipcodePartial);
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

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Area
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }
}