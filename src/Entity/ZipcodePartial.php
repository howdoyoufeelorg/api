<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 03/04/2020
 * Time: 1:37 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="zipcode_partials")
 * @ApiResource(
 *     normalizationContext={"groups"={"zipcode_partials_read"}},
 *     denormalizationContext={"groups"={"zipcode_partials_write"}}
 * )
 */
class ZipcodePartial
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
     * @Groups({"zipcode_partials_read", "zipcode_partials_write"})
     */
    private $partial;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="zipcodePartials")
     */
    private $area;

    public function __construct(string $partial)
    {
        $this->partial = $partial;
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
    public function getPartial()
    {
        return $this->partial;
    }

    /**
     * @param mixed $partial
     * @return ZipcodePartial
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;
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
     * @return ZipcodePartial
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }
}