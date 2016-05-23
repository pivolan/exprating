<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;

/**
 * ProductRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    const LIMIT_MAIN_PAGE_NEW_PRODUCTS = 6;
    const LIMIT_MAIN_PAGE_POPULAR_PRODUCTS = 6;
    const LIMIT_PRODUCT_PAGE_SIMILAR_PRODUCTS = 3;

    /**
     * @param Category $category
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByFilterQuery(ProductFilter $productFilter)
    {
        $category = $productFilter->getCategory();
        $categories = $this->_em->getRepository('AppBundle:Category')->getChildrenIds($category);
        $isEnabled = ($productFilter->getStatus() == ProductFilter::STATUS_ALL);

        $qb = $this->createQueryBuilder('a')
            ->where('a.category IN (:categories)')
            ->andWhere('a.isEnabled = :is_enabled')
            ->setParameter('categories', $categories)
            ->setParameter('is_enabled', $isEnabled);
        if (!$isEnabled) {
            if ($productFilter->getStatus() == ProductFilter::STATUS_FREE) {
                $qb->andWhere('a.expertUser IS NULL');
            } elseif ($productFilter->getStatus() == ProductFilter::STATUS_WAIT) {
                $qb->andWhere('a.expertUser IS NOT NULL')
                    ->innerJoin('a.curatorDecisions', 'd')
                    ->andWhere('d.curator = :curator')
                    ->andWhere('d.status = :status')
                    ->setParameter('curator', $productFilter->getCurator())
                    ->setParameter('status', CuratorDecision::STATUS_WAIT);
            }
        }
        if ($productFilter->getSearchString()) {
            $qb->andWhere('a.name LIKE :searchString')
                ->setParameter('searchString', '%'.$productFilter->getSearchString().'%');
        }
        $qb->orderBy('a.'.$productFilter->getSortField(), $productFilter->getSortDirection());

        return $qb->getQuery();
    }

    public function findFree()
    {
        return $this->findBy(['isEnabled' => false]);
    }

    /**
     * @return array|Product[]
     */
    public function findPopular()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.isEnabled = :isEnabled')
            ->orderBy('a.visitsCount', 'DESC')
            ->setMaxResults(self::LIMIT_MAIN_PAGE_NEW_PRODUCTS)
            ->setParameter('isEnabled', true)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function findNew()
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.isEnabled = :isEnabled')
            ->orderBy('a.enabledAt', 'DESC')
            ->setMaxResults(self::LIMIT_MAIN_PAGE_NEW_PRODUCTS)
            ->setParameter('isEnabled', true)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Product $product
     *
     * @return array|Product[]
     */
    public function findSimilar(Product $product)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.isEnabled = :isEnabled')
            ->andWhere('a.category = :category')
            ->andWhere('a.minPrice > :minPrice')
            ->andWhere('a.minPrice < :maxPrice')
            ->andWhere('a.id != :productId')
            ->orderBy('a.visitsCount', 'DESC')
            ->setMaxResults(self::LIMIT_PRODUCT_PAGE_SIMILAR_PRODUCTS)
            ->setParameter('category', $product->getCategory())
            ->setParameter('isEnabled', true)
            ->setParameter('productId', $product->getId())
            ->setParameter('minPrice', $product->getMinPrice() * 0.8)
            ->setParameter('maxPrice', $product->getMinPrice() * 1.2)
            ->getQuery();

        return $query->getResult();
    }

    public function findByExpertPublishedQuery(User $expert, Category $category = null)
    {
        $queryBuilder = $this->qbByExpert($expert);
        if ($category) {
            $categories = $this->_em->getRepository('AppBundle:Category')->getChildrenIds($category);
            $queryBuilder->andWhere('a.category IN (:category)')->setParameter('category', $categories);
        }
        $query = $queryBuilder
            ->getQuery();

        return $query;
    }

    public function findByExpertNotPublishedQuery(User $expert, Category $category = null)
    {
        $queryBuilder = $this->qbByExpert($expert)
            ->setParameter('isEnabled', false);
        if ($category) {
            $categories = $this->_em->getRepository('AppBundle:Category')->getChildrenIds($category);
            $queryBuilder->andWhere('a.category IN (:category)')->setParameter('category', $categories);
        }
        $query = $queryBuilder
            ->getQuery();

        return $query;
    }

    /**
     * @param Category[] $categories
     *
     * @return \Doctrine\ORM\Query
     */
    public function findFreeByCategoriesQuery($categories)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.expertUser IS NULL')
            ->andWhere('a.category IN (:category)')
            ->setParameter('category', $categories);

        $query = $qb->getQuery();

        return $query;
    }

    public function findByCharacteristicsQuery(CommonProductSearch $commonProductSearch, Category $category)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.isEnabled = :isEnabled')
            ->andWhere('a.category = :category')
            ->setParameter('category', $category)
            ->setParameter('isEnabled', false)
            ->orderBy('a.id', 'DESC');
        if ($commonProductSearch->getName()) {
            $qb->andWhere('a.name LIKE :name')
                ->setParameter('name', '%'.$commonProductSearch->getName().'%');
        }
        if ($commonProductSearch->getPriceGTE()) {
            $qb->andWhere('a.minPrice >= :priceGte')
                ->setParameter('priceGte', $commonProductSearch->getPriceGTE());
        }
        if ($commonProductSearch->getPriceLTE()) {
            $qb->andWhere('a.minPrice <= :priceLte')
                ->setParameter('priceLte', $commonProductSearch->getPriceLTE());
        }
        foreach ($commonProductSearch->getCharacteristics() as $key => $characteristic) {
            $alias = 'c'.$key;
            $key1 = 'c1'.$key;
            $key2 = 'c2'.$key;
            $key3 = 'c3'.$key;
            $name = $characteristic->getName();
            switch ($characteristic->getType()) {
                case Characteristic::TYPE_STRING:
                    if ($characteristic->getValue()) {
                        $qb->innerJoin(
                            'a.productCharacteristics',
                            $alias,
                            'WITH',
                            "$alias.product=a.id AND $alias.characteristic=:$key1 AND $alias.valueString LIKE :$key2"
                        )
                            ->setParameter($key1, $name)
                            ->setParameter($key2, '%'.$characteristic->getValue().'%');
                    }
                    break;
                case Characteristic::TYPE_DECIMAL:
                    $condition = '';
                    if ($characteristic->getValueGTE() || $characteristic->getValueLTE()) {
                        $condition = "$alias.product=a.id AND $alias.characteristic=:$key1";
                        $qb->setParameter($key1, $name);
                    }
                    if ($characteristic->getValueGTE()) {
                        $condition .= " AND $alias.valueDecimal >= :$key2";
                        $qb->setParameter($key2, $characteristic->getValueGTE());
                    }
                    if ($characteristic->getValueLTE()) {
                        $condition .= " AND $alias.valueDecimal <= :$key3";
                        $qb->setParameter($key3, $characteristic->getValueLTE());
                    }
                    if ($condition) {
                        $qb->innerJoin('a.productCharacteristics', $alias, 'WITH', $condition);
                    }
                    break;
                case Characteristic::TYPE_INT:
                    $condition = '';
                    if ($characteristic->getValueGTE() || $characteristic->getValueLTE()) {
                        $condition = "$alias.product=a.id AND $alias.characteristic=:$key1";
                        $qb->setParameter($key1, $name);
                    }
                    if ($characteristic->getValueGTE()) {
                        $condition .= " AND $alias.valueInt >= :$key2";
                        $qb->setParameter($key2, $characteristic->getValueGTE());
                    }
                    if ($characteristic->getValueLTE()) {
                        $condition .= " AND $alias.valueInt <= :$key3";
                        $qb->setParameter($key3, $characteristic->getValueLTE());
                    }
                    if ($condition) {
                        $qb->innerJoin('a.productCharacteristics', $alias, 'WITH', $condition);
                    }
                    break;
            }
        }

        return $qb->getQuery();
    }

    public function findReserved()
    {
        $date = new \DateTime('-1 month');
        $qb = $this->createQueryBuilder('a')
            ->where('a.reservedAt < :monthAgoDate')
            ->andWhere('a.reservedAt IS NOT NULL')
            ->setParameter('monthAgoDate', $date);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $expert
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function qbByExpert(User $expert)
    {
        return $this->createQueryBuilder('a')
            ->where('a.isEnabled = :isEnabled')
            ->andWhere('a.expertUser = :expert')
            ->orderBy('a.enabledAt', 'DESC')
            ->setParameter('isEnabled', true)
            ->setParameter(':expert', $expert);
    }

    public function getProductsWithoutCategoryQuery()
    {
        return $this->createQueryBuilder('a')
            ->where('a.category IS NULL')
            ->getQuery();
    }

    public function getAllQuery()
    {
        return $this->createQueryBuilder('a')
            ->getQuery();
    }

    /**
     * Получить товары, категория которых не листовая, т.е. не последняя и имеет потомков
     * @return \Doctrine\ORM\Query
     */
    public function getWithNotLisnCategoryQuery()
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.category', 'b')
            ->innerJoin('b.children', 'c')
            ->getQuery();
    }

    /**
     * @param Category $category
     *
     * @return \Doctrine\ORM\Query
     */
    public function getQueryByCategory(Category $category)
    {
        return $this->createQueryBuilder('a')
            ->where('a.category = :category')
            ->setParameter('category', $category)
            ->getQuery();
    }
}
