<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * ShopRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function findExperts()
    {
        $qb = $this->queryBuilderByRole(User::ROLE_EXPERT);
        $qb->andWhere('u.enabled = :isEnabled')
            ->setParameter('isEnabled', true);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $role
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryBuilderByRole($role = User::ROLE_USER)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');

        return $qb;
    }
}
