<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/04/2020
 * Time: 11:14 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="instruction_contents")
 * @ApiResource(
 *     normalizationContext={"groups"={"instructions_read"}},
 *     denormalizationContext={"groups"={"instructions_write"}}
 * )
 * @Gedmo\Loggable
 */
class InstructionContent
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Instruction", inversedBy="contents")
     */
    private $instruction;
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $language;
    /**
     * @ORM\Column(type="text", length=65535)
     * @Groups({"instructions_read", "instructions_write"})
     */
    private $content;

    public function __construct(string $language, string $content)
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
     * @return Instruction
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * @param Instruction $instruction
     * @return InstructionContent
     */
    public function setInstruction(Instruction $instruction)
    {
        $this->instruction = $instruction;
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
     * @return InstructionContent
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
     * @return InstructionContent
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}