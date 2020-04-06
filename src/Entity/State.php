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
 * @ORM\Table(name="states")
 * @ApiResource(
 *     normalizationContext={"groups"={"states_read", "geoentity_read"}},
 *     denormalizationContext={"groups"={"states_write"}}
 * )
 */
class State extends AbstractGeoEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="states")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Area", mappedBy="state", cascade={"persist"}, orphanRemoval=true)
     */
    private $areas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instruction", mappedBy="state")
     */
    private $instructions;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="states")
     */
    private $users;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->areas = new ArrayCollection();
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return State
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @param mixed $states
     * @return State
     */
    public function addArea(Area $area)
    {
        $area->setState($this);
        $this->areas->add($area);
        return $this;
    }

    /**
     * @return Instruction[]
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param mixed $instructions
     * @return State
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return State
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }
}