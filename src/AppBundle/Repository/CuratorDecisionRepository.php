<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

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
            ->select('count(a)')
            ->where('a.status IN (:statuses)')
            ->andWhere('a.product = :product')
            ->setParameter('statuses', [CuratorDecision::STATUS_APPROVE, CuratorDecision::STATUS_WAIT])
            ->setParameter('product', $product);
        return ($qb->getQuery()->getSingleScalarResult() > 0);
    }

    public function countNew(User $curator)
    {
        $qb = $this->createQueryBuilder('a')->select('count(a)')
            ->where('a.curator = :curator')
            ->andWhere('a.status = :status')
            ->setParameter('curator', $curator)
            ->setParameter('status', CuratorDecision::STATUS_WAIT);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function waitByCuratorByProduct(User $curator, Product $product)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.curator = :curator')
            ->andWhere('a.status = :status')
            ->andWhere('a.product = :product')
            ->setParameter('curator', $curator)
            ->setParameter('product', $product)
            ->setParameter('status', CuratorDecision::STATUS_WAIT);
        return $qb->getQuery();
    }
}
