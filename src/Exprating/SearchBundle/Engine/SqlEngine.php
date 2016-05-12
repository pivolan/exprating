<?php

/**
 * Date: 08.02.16
 * Time: 16:27.
 */

namespace Exprating\SearchBundle\Engine;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Exprating\SearchBundle\Dto\SearchCriteria;

class SqlEngine implements EngineInterface
{
    /** @var  EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $string
     *
     * @return Product[]
     */
    public function search($string, SearchCriteria $searchCriteria)
    {
        $qb = $this->entityManager->getRepository($searchCriteria->getRepositoryName())->createQueryBuilder('p');
        foreach ($searchCriteria->getCriteria() as $key => $value) {
            $qb->andWhere("p.$key = :$key")
                ->setParameter($key, $value);
        }

        $words = explode(' ', $string);
        $sqlPart = [];
        foreach ($words as $key => $word) {
            $processedWord = trim($word);
            if (mb_strlen($processedWord, 'UTF-8') > 2) {
                foreach ($searchCriteria->getFields() as $fieldName) {
                    $sqlPart[] = "p.$fieldName LIKE :word$fieldName$key";
                    $qb->setParameter("word$fieldName$key", '%'.$processedWord.'%');
                }
            }
        }
        $qb->andWhere(implode(' OR ', $sqlPart));
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
