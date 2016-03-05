<?php

/**
 * Date: 08.02.16
 * Time: 16:41.
 */

namespace Exprating\SearchBundle\Engine;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Exprating\SearchBundle\Sphinx\IndexNames;
use IAkumaI\SphinxsearchBundle\Search\Sphinxsearch;

class SphinxEngine implements EngineInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Sphinxsearch
     */
    private $sphinxSearch;

    /**
     * SphinxEngine constructor.
     *
     * @param EntityManager $entityManager
     * @param Sphinxsearch  $sphinxSearch
     */
    public function __construct(EntityManager $entityManager, Sphinxsearch $sphinxSearch)
    {
        $this->entityManager = $entityManager;
        $this->sphinxSearch = $sphinxSearch;
    }


    /**
     * @param $string
     *
     * @return Product[]
     */
    public function search($string)
    {
        return $this->sphinxSearch->searchEx($string, IndexNames::INDEX_PRODUCT);
    }
}
