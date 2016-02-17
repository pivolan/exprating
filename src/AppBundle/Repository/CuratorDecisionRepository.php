<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;

/**
 * CuratorDecisionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CuratorDecisionRepository extends \Doctrine\ORM\EntityRepository
{
    public function IsExists(Product $product)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(*)')
            ->where('a.status IN :statuses')
            ->andWhere('a.product = :product')
            ->setParameter('statuses', [CuratorDecision::STATUS_APPROVE, CuratorDecision::STATUS_WAIT])
            ->setParameter('product', $product);
        return ($qb->getQuery()->getSingleScalarResult() > 0);
    }
}