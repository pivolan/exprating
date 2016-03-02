<?php

/**
 * Date: 08.02.16
 * Time: 16:41.
 */

namespace Exprating\SearchBundle\Engine;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class SphinxEngine implements EngineInterface
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
        return [];
    }
}
