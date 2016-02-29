<?php
/**
 * Date: 24.02.16
 * Time: 17:35
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function getByUserQuery(User $user)
    {
        $query = $this->createQueryBuilder('node')
            ->select('node')
            ->innerJoin('node.admins', 'u', null)
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery();
        return $query;
    }

    /**
     * @return Category[]
     */
    public function getLastLevel()
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.children', 'children')
            ->groupBy('a.slug')
            ->having('count(children.slug) = :counter')
            ->setParameter('counter', 0);
        return $qb->getQuery()->getResult();
    }

    public function getFirstLevel()
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.parent IS NULL')
            ->leftJoin('a.children', 'children')
            ->leftJoin('a.peopleGroups', 'people_groups');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}