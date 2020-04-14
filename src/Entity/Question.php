<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 11:53 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @ORM\Table(name="questions")
 * @ApiResource(
 *     normalizationContext={"groups"={"questions_read", "labels_data_include"}},
 *     denormalizationContext={"groups"={"questions_write"}, "enable_max_depth"=true}
 * )
 * @Gedmo\Loggable
 */
class Question
{
    const TYPE_SLIDER = 'slider';
    const TYPE_YESNO = 'yesno';
    const TYPE_ENTRY = 'entry';

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"questions_read", "questions_write"})
     */
    private $questionNo;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"questions_read", "questions_write"})
     */
    private $questionWeight;
    /**
     * @ORM\Column(type="string")
     * @Groups({"questions_read", "questions_write"})
     */
    private $type;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionLabel", mappedBy="question", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups({"labels_data_include", "questions_write"})
     * @MaxDepth(3)
     */
    private $labels;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"questions_read", "questions_write"})
     */
    private $description;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"questions_read", "questions_write"})
     */
    private $required = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"questions_read", "questions_write"})
     */
    private $requiresAdditionalData;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"questions_read", "questions_write"})
     */
    private $additionalDataType;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdditionalDataLabel", mappedBy="question", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Groups({"labels_data_include", "questions_write"})
     * @MaxDepth(3)
     */
    private $additionalDataLabels;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"questions_read", "questions_write"})
     */
    private $disabled = false;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->additionalDataLabels = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'questionNo' => $this->getQuestionNo(),
        ];
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
    public function getQuestionNo()
    {
        return $this->questionNo;
    }

    /**
     * @param mixed $questionNo
     * @return Question
     */
    public function setQuestionNo($questionNo)
    {
        $this->questionNo = $questionNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestionWeight()
    {
        return $this->questionWeight;
    }

    /**
     * @param mixed $questionWeight
     * @return Question
     */
    public function setQuestionWeight($questionWeight)
    {
        $this->questionWeight = $questionWeight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Question
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return QuestionLabel[]|ArrayCollection
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param QuestionLabel $label
     * @return Question
     */
    public function addLabel(QuestionLabel $label)
    {
        if(!$this->labels->contains($label)) {
            $label->setQuestion($this);
            $this->labels[] = $label;
        }
        return $this;
    }

    /**
     * @param QuestionLabel $label
     * @return Question
     */
    public function removeLabel(QuestionLabel $label)
    {
        if($this->labels->contains($label)) {
            $this->labels->removeElement($label);
            if ($label->getQuestion() === $this) {
                $label->setQuestion(null);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Question
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     * @return Question
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequiresAdditionalData()
    {
        return $this->requiresAdditionalData;
    }

    /**
     * @param mixed $requiresAdditionalData
     * @return Question
     */
    public function setRequiresAdditionalData($requiresAdditionalData)
    {
        $this->requiresAdditionalData = $requiresAdditionalData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalDataType()
    {
        return $this->additionalDataType;
    }

    /**
     * @param mixed $additionalDataType
     * @return Question
     */
    public function setAdditionalDataType($additionalDataType)
    {
        $this->additionalDataType = $additionalDataType;
        return $this;
    }

    /**
     * @return AdditionalDataLabel[] | ArrayCollection
     */
    public function getAdditionalDataLabels()
    {
        return $this->additionalDataLabels;
    }

    /**
     * @param AdditionalDataLabel $additionalDataLabel
     * @return Question
     */
    public function addAdditionalDataLabel(AdditionalDataLabel $additionalDataLabel)
    {
        if(! $this->additionalDataLabels->contains($additionalDataLabel)) {
            $additionalDataLabel->setQuestion($this);
            $this->additionalDataLabels[] = $additionalDataLabel;
        }
        return $this;
    }

    /**
     * @param AdditionalDataLabel $additionalDataLabel
     * @return Question
     */
    public function removeAdditionalDataLabel(AdditionalDataLabel $additionalDataLabel)
    {
        if($this->additionalDataLabels->contains($additionalDataLabel)) {
            $this->additionalDataLabels->remove($additionalDataLabel);
            if($additionalDataLabel->getQuestion() === $this) {
                $additionalDataLabel->setQuestion(null);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param mixed $disabled
     * @return Question
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}