<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/04/2020
 * Time: 1:00 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="front_element_contents")
 * @ApiResource(
 *     normalizationContext={"groups"={"front_element_contents_read"}},
 *     denormalizationContext={"groups"={"front_element_contents_write"}}
 * )
 */
class FrontElementContent
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var FrontElement
     * @ORM\ManyToOne(targetEntity="App\Entity\FrontElement", inversedBy="contents")
     */
    private $frontElement;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;
    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $content;

    public function __construct(string $language = '', string $content = '')
    {
        $this->language = $language;
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FrontElement
     */
    public function getFrontElement(): FrontElement
    {
        return $this->frontElement;
    }

    /**
     * @param FrontElement $frontElement
     * @return FrontElementContent
     */
    public function setFrontElement(FrontElement $frontElement): FrontElementContent
    {
        $this->frontElement = $frontElement;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return FrontElementContent
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return FrontElementContent
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}