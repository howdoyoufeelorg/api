<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 26/03/2020
 * Time: 12:42 pm
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractLabel
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @Groups({"label_read", "labels_data_include", "label_write"})
     */
    private $language;
    /**
     * @ORM\Column(type="string")
     * @Groups({"label_read", "labels_data_include", "label_write"})
     */
    private $label;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="labels")
     * @Groups({"label_read"})
     * @MaxDepth(2)
     */
    private $question;

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
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
        return $this;
    }
}