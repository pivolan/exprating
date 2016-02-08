<?php
/**
 * Date: 08.02.16
 * Time: 16:27
 */

namespace Exprating\SearchBundle\Engine;


use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

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
    public function search($string)
    {
        $qb = $this->entityManager->getRepository('AppBundle:Product')->createQueryBuilder('p');
        $qb->where('p.isEnabled = :isEnabled')
            ->setParameter('isEnabled', true);

        $words = explode(' ', $string);
        $sqlPart = [];
        foreach ($words as $key => $word) {
            $processedWord = trim($word);
            if (mb_strlen($processedWord, 'UTF-8') > 2) {
                $sqlPart[] = "p.name LIKE :word$key";
                $qb->setParameter("word$key", '%' . $processedWord . '%');
            }
        }
        $qb->andWhere(implode(' OR ', $sqlPart))
            ->orderBy('p.id', 'ASC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}