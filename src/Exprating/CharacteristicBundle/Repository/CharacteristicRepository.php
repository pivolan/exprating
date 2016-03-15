<?php

namespace Exprating\CharacteristicBundle\Repository;

use Doctrine\ORM\AbstractQuery;

/**
 * CharacteristicRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CharacteristicRepository extends \Doctrine\ORM\EntityRepository
{
    public function getIdNameByQ($q, $pageLimit, $skip)
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.slug as id, a.label as text')
            ->where('a.label LIKE :q')
            ->setParameter('q', '%'.$q.'%')
            ->setMaxResults($pageLimit)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        return $result;
    }
}
