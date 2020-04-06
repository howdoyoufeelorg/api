<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 23/03/2020
 * Time: 5:25 pm
 */

namespace App\Repository;

use App\Entity\Instruction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

class InstructionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instruction::class);
    }

    /**
     * @return Instruction[]
     */
    public function findInstructions($geoEntities, $severity)
    {
        $countryWhere = $stateWhere = $areaWhere = $zipWhere = null;
        $parameters = ['severity' => $severity];
        if(array_key_exists('country', $geoEntities)) {
            $parameters['country'] = $geoEntities['country'];
            $countryWhere = 'i.country = :country';
        }
        if(array_key_exists('state', $geoEntities)) {
            $parameters['state'] = $geoEntities['state'];
            $stateWhere = 'i.state = :state';
        }
        if(array_key_exists('area', $geoEntities)) {
            $parameters['area'] = $geoEntities['area'];
            $areaWhere = 'i.area = :area';
        }
        if(array_key_exists('zipcode', $geoEntities)) {
            $parameters['zipcode'] = $geoEntities['zipcode'];
            $zipWhere = 'i.zipcode = :zipcode';
        }
        $qb = $this->createQueryBuilder('i');
        $qb->where('i.severity = :severity');
        $qb->andWhere($qb->expr()->orX($countryWhere, $stateWhere, $areaWhere, $zipWhere));
        $qb->setParameters($parameters);
        $qb->orderBy('i.updatedAt', 'DESC');
        $query = $qb->getQuery();
        return $query->execute();
    }
}