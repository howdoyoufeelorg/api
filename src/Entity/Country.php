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
 * @ORM\Table(name="countries")
 * @ApiResource(
 *     attributes={"pagination_enabled"=false},
 *     normalizationContext={"groups"={"countries_read", "geoentity_read"}},
 *     denormalizationContext={"groups"={"countries_write", "geoentity_write"}}
 * )
 * @ApiFilter(OrderFilter::class, properties={"id", "name"})
 * @ApiFilter(SearchFilter::class, properties={"id": "exact"})
 */
class Country extends AbstractGeoEntity
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\State", mappedBy="country", cascade={"persist"}, orphanRemoval=true)
     * @Groups({"countries_read"})
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

    public function __construct(string $name = '')
    {
        parent::__construct($name);
        $this->states = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @param User $user
     * @return Country
     */
    public function removeUser(User $user)
    {
        if($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
        return $this;
    }
}