<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 26/03/2020
 * Time: 12:42 pm
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="additional_data_labels")
 * @ApiResource(
 *     normalizationContext={"groups"={"label_read"}},
 *     denormalizationContext={"groups"={"label_write"}}
 * )
 * @Gedmo\Loggable
 */
class AdditionalDataLabel extends AbstractLabel
{

}