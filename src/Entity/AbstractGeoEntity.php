<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 03/04/2020
 * Time: 11:49 am
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractGeoEntity
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"geoentity_read", "geoentity_write"})
     */
    private $name;
    /**
     * @ORM\Column(type="json")
     * @Groups({"geoentity_read", "geoentity_write"})
     */
    private $webResources = [];
    /**
     * @ORM\Column(type="json")
     * @Groups({"geoentity_read", "geoentity_write"})
     */
    private $twitterResources = [];
    /**
     * @ORM\Column(type="json")
     * @Groups({"geoentity_read", "geoentity_write"})
     */
    private $officialWebResources = [];
    /**
     * @ORM\Column(type="json")
     * @Groups({"geoentity_read", "geoentity_write"})
     */
    private $phoneNumbers = [];

    /**
     * AbstractGeoEntity constructor.
     * @param $name
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return AbstractGeoEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return AbstractGeoEntity
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getWebResources(): array
    {
        return $this->webResources;
    }

    /**
     * @param array $webResources
     * @return AbstractGeoEntity
     */
    public function setWebResources(array $webResources): AbstractGeoEntity
    {
        $this->webResources = $webResources;
        return $this;
    }

    /**
     * @param string $webResource
     * @return AbstractGeoEntity
     */
    public function addWebResource(string $webResource): AbstractGeoEntity
    {
        $this->webResources[] = $webResource;
        return $this;
    }

    /**
     * @return array
     */
    public function getTwitterResources(): array
    {
        return $this->twitterResources;
    }

    /**
     * @param array $twitterResources
     * @return AbstractGeoEntity
     */
    public function setTwitterResources(array $twitterResources): AbstractGeoEntity
    {
        $this->twitterResources = $twitterResources;
        return $this;
    }

    /**
     * @param string $twitterResource
     * @return AbstractGeoEntity
     */
    public function addTwitterResource(string $twitterResource): AbstractGeoEntity
    {
        $this->twitterResources[] = $twitterResource;
        return $this;
    }

    /**
     * @return array
     */
    public function getOfficialWebResources(): array
    {
        return $this->officialWebResources;
    }

    /**
     * @param array $officialWebResources
     * @return AbstractGeoEntity
     */
    public function setOfficialWebResources(array $officialWebResources): AbstractGeoEntity
    {
        $this->officialWebResources = $officialWebResources;
        return $this;
    }

    /**
     * @param string $officialWebResource
     * @return AbstractGeoEntity
     */
    public function addOfficialWebResource(string $officialWebResource): AbstractGeoEntity
    {
        $this->officialWebResources[] = $officialWebResource;
        return $this;
    }

    /**
     * @return array
     */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /**
     * @param array $phoneNumbers
     * @return AbstractGeoEntity
     */
    public function setPhoneNumbers(array $phoneNumbers): AbstractGeoEntity
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    /**
     * @param string $phoneNumber
     * @return AbstractGeoEntity
     */
    public function addPhoneNumber(string $phoneNumber): AbstractGeoEntity
    {
        $this->phoneNumbers[] = $phoneNumber;
        return $this;
    }
}