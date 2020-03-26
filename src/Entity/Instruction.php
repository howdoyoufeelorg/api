<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 12:00 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="instructions")
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Area", inversedBy="instructions")
     */
    private $area;
    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $contents;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instructions")
     */
    private $createdBy;


}