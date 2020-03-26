<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 23/03/2020
 * Time: 5:25 pm
 */

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @return Question[]
     */
    public function findEnabledQuestions()
    {
        $qb = $this->createQueryBuilder('q');
        $qb->where('q.disabled != 1');
        $qb->orderBy('q.questionNo');
        $query = $qb->getQuery();
        return $query->execute();
    }
}