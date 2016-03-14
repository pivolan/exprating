<?php

/**
 * Date: 24.02.16
 * Time: 17:35.
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
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

    public function getProductsRecursiveQuery(Category $category)
    {
        $categories = $this->getChildrenIds($category);
        $qb = $this->_em->getRepository('AppBundle:Product')
            ->createQueryBuilder('b')
            ->where('b.category IN (:categories)')
            ->andWhere('b.expertUser IS NULL')
            ->setParameter('categories', $categories);

        return $qb->getQuery();
    }

    public function getChildrenIds(Category $category, PeopleGroup $peopleGroup = null)
    {
        $qb = $this->getChildrenQueryBuilder($category, false, null, 'ASC', true)
            ->select('node.slug');
        if ($peopleGroup) {
            $qb->innerJoin('node.peopleGroups', 'pg', 'WITH', 'pg.slug = :peopleGroup')
                ->setParameter('peopleGroup', $peopleGroup->getSlug());
        }
        $result = $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
        $ids = [];
        foreach ($result as $row) {
            $ids[] = $row['slug'];
        }

        return $ids;
    }

    public function getForJsTree(User $user = null, User $admin = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.name, a.slug as id, b.slug as parent_id')
            ->leftJoin('a.parent', 'b');
        if ($user) {
            $qb->innerJoin('a.experts', 'e')
                ->where('e.id = :user')
                ->setParameter('user', $user);
        }
        if ($admin) {
            $qb->innerJoin('a.admins', 'ad')
                ->where('ad.id = :admin')
                ->setParameter('admin', $admin);
        }

        $categories = $qb->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
        $result = [];
        foreach ($categories as $category) {
            $result[$category['id']] = $category;
        }

        return $result;
    }

    public function getIdNameByQ($q, $pageLimit, $skip)
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.slug as id, a.name as text')
            ->where('a.name LIKE :q')
            ->setParameter('q', '%'.$q.'%')
            ->setMaxResults($pageLimit)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        return $result;
    }
}
