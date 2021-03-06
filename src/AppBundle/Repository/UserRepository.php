<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * ShopRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends NestedTreeRepository
{
    public function findExpertsQuery()
    {
        $qb = $this->queryBuilderByRole(User::ROLE_EXPERT);
        $qb->andWhere('u.enabled = :isEnabled')
            ->andWhere('u.isActivated = :isEnabled')
            ->setParameter('isEnabled', true)
            ->orderBy('u.id', 'DESC');

        return $qb->getQuery();
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
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb;
    }

    /**
     * @param User $curator
     *
     * @return \Doctrine\ORM\Query
     */
    public function level2Query(User $curator)
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.curator', 'p')
            ->where('p.curator = :curator')
            ->setParameter('curator', $curator)
            ->orderBy('a.id', 'DESC');

        return $qb->getQuery();
    }

    /**
     * @param Category[] $categories
     *
     * @return User
     */
    public function getRandomByCategories($categories)
    {
        $qb = $this->createQueryBuilder('a')
            ->innerJoin('a.adminCategories', 'b', 'WITH', 'b IN (:categories)')
            ->setParameter('categories', $categories)
            ->where('a.roles LIKE :role')
            ->setParameter('role', '%'.User::ROLE_EXPERT_CATEGORY_ADMIN.'%');
        $count = $qb->select('count(a.id) as counter')->getQuery()->getSingleScalarResult();
        $user = $qb->select('a')->setFirstResult(rand(0, $count - 1))->setMaxResults(1)->getQuery()->getSingleResult();

        return $user;
    }

    public function findEmails($role = User::ROLE_EXPERT)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.email')
            ->where('a.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%');

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
