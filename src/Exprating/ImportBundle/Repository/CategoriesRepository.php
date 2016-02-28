<?php
/**
 * Date: 28.02.16
 * Time: 10:36
 */

namespace Exprating\ImportBundle\Repository;


use Exprating\ImportBundle\Entity\Categories;

class CategoriesRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return Categories[]
     */
    public function getFreeLastLevel()
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.items', 'items')
            ->groupBy('a.id')
            ->having('count(items.id) > :counter')
            ->setParameter('counter', 0);

        return $qb->getQuery()->getResult();
    }
}