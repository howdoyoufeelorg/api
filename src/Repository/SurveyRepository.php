<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 18/03/2020
 * Time: 5:45 pm
 */

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }
}