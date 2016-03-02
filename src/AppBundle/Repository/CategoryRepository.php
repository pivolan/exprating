<?php

/**
 * Date: 24.02.16
 * Time: 17:35.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Doctrine\ORM\AbstractQuery;
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
            ->select('a, b, c, d, e, f')
            ->where('a.parent IS NULL')
            ->leftJoin('a.peopleGroups', 'b')
            ->leftJoin('a.ratingSettings', 'c')
            ->leftJoin('a.children', 'd')
            ->leftJoin('d.peopleGroups', 'e')
            ->leftJoin('d.ratingSettings', 'f');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getProductsRecursiveQueryBuilder(Category $category)
    {
        $categories = $this->getChildrenIds($category);
        $qb = $this->_em->getRepository('AppBundle:Product')
            ->createQueryBuilder('b')
            ->where('b.category IN (:categories)')
            ->setParameter('categories', $categories);

        return $qb;
    }

    public function getProductsRecursiveQuery(Category $category)
    {
        $qb = $this->getProductsRecursiveQueryBuilder($category);

        return $qb->getQuery();
    }

    public function getChildrenIds(Category $category)
    {
        $qb = $this->getChildrenQueryBuilder($category, false, null, 'ASC', true)
            ->select('node.slug');
        $result = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
        $ids = [];
        foreach ($result as $row) {
            $ids[] = $row['slug'];
        }

        return $ids;
    }
}
