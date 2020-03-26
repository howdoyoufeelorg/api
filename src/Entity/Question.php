<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 19/03/2020
 * Time: 11:53 am
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @ORM\Table(name="questions")
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
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
     * @Groups({"read", "write"})
     */
    private $questionNo;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read", "write"})
     */
    private $questionWeight;
    /**
     * @ORM\Column(type="string")
     * @Groups({"read", "write"})
     */
    private $type;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionLabel", mappedBy="question")
     * @Groups({"read", "write"})
     */
    private $labels;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $description;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     */
    private $required = false;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read", "write"})
     */
    private $requiresAdditionalData;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"read", "write"})
     */
    private $additionalDataType;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdditionalDataLabel", mappedBy="question")
     * @Groups({"read", "write"})
     */
    private $additionalDataLabels;
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     */
    private $disabled = false;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    private $answers;

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
     * @return QuestionLabel[]
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
        $label->setQuestion($this);
        $this->labels[] = $label;
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
     * @return mixed
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
        $additionalDataLabel->setQuestion($this);
        $this->additionalDataLabels[] = $additionalDataLabel;
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