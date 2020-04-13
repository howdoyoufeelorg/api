<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ApiResource(
 *     normalizationContext={"groups"={"users_read"}},
 *     denormalizationContext={"groups"={"users_write"}}
 * )
 */
class User implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_EDITOR = 'ROLE_EDITOR';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     * @Groups({"users_read", "users_write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users_read", "users_write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"users_read", "users_write"})
     */
    private $firstname;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"users_read", "users_write"})
     */
    private $middlename;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"users_read", "users_write"})
     */
    private $lastname;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Country", inversedBy="users")
     * @Groups({"users_read", "users_write"})
     */
    private $countries;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\State", inversedBy="users")
     * @Groups({"users_read", "users_write"})
     */
    private $states;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Area", inversedBy="users")
     * @Groups({"users_read", "users_write"})
     */
    private $areas;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Instruction", mappedBy="createdBy")
     */
    private $instructions;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrationHash;
    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $confirmed = false;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
        $this->states = new ArrayCollection();
        $this->areas = new ArrayCollection();
    }

    public function getFullname()
    {
        return "$this->lastname, $this->firstname";
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * @param mixed $middlename
     * @return User
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return Country[]|ArrayCollection
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param Country $country
     * @return User
     */
    public function addCountry(Country $country)
    {
        $country->addUser($this);
        $this->countries[] = $country;
        return $this;
    }

    public function removeCountry(Country $country)
    {
        if ($this->countries->contains($country)) {
            $this->countries->removeElement($country);
            if($country->getUsers()->contains($this)) {
                $country->removeUser($this);
            }
        }
        return $this;
    }

    /**
     * @return State[]|ArrayCollection
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param State $state
     * @return User
     */
    public function addState(State $state)
    {
        $state->addUser($this);
        $this->states[] = $state;
        return $this;
    }

    public function removeState(State $state)
    {
        if ($this->states->contains($state)) {
            $this->states->removeElement($state);
            if($state->getUsers()->contains($this)) {
                $state->removeUser($this);
            }
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * @param Area $area
     * @return User
     */
    public function addArea(Area $area)
    {
        $area->addUser($this);
        $this->areas[] = $area;
        return $this;
    }

    /**
     * @param Area $area
     * @return User
     */
    public function removeArea(Area $area)
    {
        if ($this->areas->contains($area)) {
            $this->areas->removeElement($area);
            if($area->getUsers()->contains($this)) {
                $area->removeUser($this);
            }
        }
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
     * @return User
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrationHash()
    {
        return $this->registrationHash;
    }

    /**
     * @param mixed $registrationHash
     * @return User
     */
    public function setRegistrationHash($registrationHash)
    {
        $this->registrationHash = $registrationHash;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     * @return User
     */
    public function setConfirmed(bool $confirmed): User
    {
        $this->confirmed = $confirmed;
        return $this;
    }
}
