<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 26/03/2020
 * Time: 12:42 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation as API;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity
 * @ORM\Table(name="question_labels")
 * @API\ApiResource(
 *     normalizationContext={"groups"={"label_read"}},
 *     denormalizationContext={"groups"={"label_write"}}
 * )
 * @API\ApiFilter(SearchFilter::class, properties={"id": "exact", "question_id": "exact"})
 * @Gedmo\Loggable
 */
class QuestionLabel extends AbstractLabel
{

}