<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/04/2020
 * Time: 1:03 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="front_elements")
 * @ApiResource(
 *     normalizationContext={"groups"={"front_elements_read"}},
 *     denormalizationContext={"groups"={"front_elements_write"}}
 * )
 */
class FrontElement
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
     */
    private $elementId;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FrontElementContent", mappedBy="frontElement", cascade={"persist"}, orphanRemoval=true)
     */
    private $contents;

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
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * @param mixed $elementId
     * @return FrontElement
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
        return $this;
    }

    /**
     * @return FrontElementContent[]|ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param FrontElementContent $content
     * @return FrontElement
     */
    public function addContent(FrontElementContent $content)
    {
        $content->setFrontElement($this);
        $this->contents[] = $content;
        return $this;
    }
}