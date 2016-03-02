<?php

/**
 * Date: 28.02.16
 * Time: 10:36.
 */

namespace Exprating\ImportBundle\Repository;

class ItemRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getAllQuery()
    {
        $qb = $this->createQueryBuilder('a');

        return $qb->getQuery();
    }
}
