<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 03/04/2020
 * Time: 11:37 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="countries")
 * @ApiResource(
 *     normalizationContext={"groups"={"countries_read", "geoentity_read"}},
 *     denormalizationContext={"groups"={"countries_write"}}
 * )
 */
class Country extends AbstractGeoEntity
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\State", mappedBy="country", cascade={"persist"}, orphanRemoval=true)
     */
    private $states;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instruction", mappedBy="country")
     */
    private $instructions;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="countries")
     */
    private $users;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->states = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param mixed $states
     * @return Country
     */
    public function addState(State $state)
    {
        $state->setCountry($this);
        $this->states->add($state);
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
     * @return Country
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
     * @return Country
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }
}