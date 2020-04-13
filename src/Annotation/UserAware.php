<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 13/04/2020
 * Time: 3:23 pm
 */

namespace App\Annotation;
use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target("CLASS")
 */
final class UserAware
{
    public $userFieldName;
}