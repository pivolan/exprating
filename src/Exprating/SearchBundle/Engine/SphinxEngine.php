<?php

/**
 * Date: 08.02.16
 * Time: 16:41.
 */

namespace Exprating\SearchBundle\Engine;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Exprating\SearchBundle\Dto\SearchCriteria;
use Exprating\SearchBundle\Sphinx\IndexNames;
use IAkumaI\SphinxsearchBundle\Search\Sphinxsearch;

class SphinxEngine implements EngineInterface
{
    /**
     * @var Sphinxsearch
     */
    private $sphinxSearch;

    /**
     * SphinxEngine constructor.
     *
     * @param Sphinxsearch $sphinxSearch
     */
    public function __construct(Sphinxsearch $sphinxSearch)
    {
        $this->sphinxSearch = $sphinxSearch;
    }


    /**
     * @param $string
     *
     * @return Product[]
     */
    public function search($string, SearchCriteria $searchCriteria)
    {
        $this->sphinxSearch->SetLimits($searchCriteria->getOffset(), $searchCriteria->getLimit());
        $result = $this->sphinxSearch->searchEx($string, $searchCriteria->getIndexName());
        $entities = [];
        if (isset($result['matches'])) {
            foreach ($result['matches'] as $match) {
                if (is_object($match['entity'])) {
                    $entities[] = $match['entity'];
                }
            }
        }

        return $entities;
    }
}
