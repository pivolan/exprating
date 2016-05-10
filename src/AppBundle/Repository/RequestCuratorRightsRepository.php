<?php

namespace AppBundle\Repository;

use AppBundle\Entity\RequestCuratorRights;
use AppBundle\Entity\User;

/**
 * RequestCuratorRightsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RequestCuratorRightsRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param User $expert
     * @param      $period
     *
     * @return array|RequestCuratorRights[]
     */
    public function findLastByPeriod(User $expert, $period)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.expert = :expert')
            ->andWhere('a.createdAt > :date')
            ->setParameter('expert', $expert)
            ->setParameter('date', new \DateTime("-$period days"))
            ->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}