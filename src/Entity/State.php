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
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity
 * @ORM\Table(name="states")
 * @ApiResource(
 *     normalizationContext={"groups"={"states_read", "geoentity_read"}},
 *     denormalizationContext={"groups"={"states_write", "geoentity_write"}}
 * )
 * @ApiFilter(OrderFilter::class, properties={"id", "name"})
 * @ApiFilter(SearchFilter::class, properties={"id": "exact"})
 */
class State extends AbstractGeoEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="states")
     * @Groups({"states_read"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Area", mappedBy="state", cascade={"persist"}, orphanRemoval=true)
     * @Groups({"states_read"})
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

    public function __construct(string $name = '')
    {
        parent::__construct($name);
        $this->areas = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @return User[]|ArrayCollection
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

    /**
     * @param User $user
     * @return State
     */
    public function removeUser(User $user)
    {
        if($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
        return $this;
    }
}