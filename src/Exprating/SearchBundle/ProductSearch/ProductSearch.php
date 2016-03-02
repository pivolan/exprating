<?php

/**
 * Date: 08.02.16
 * Time: 16:34.
 */

namespace Exprating\SearchBundle\ProductSearch;

use AppBundle\Entity\Product;
use Exprating\SearchBundle\Engine\EngineInterface as SearchEngineInterface;
use Exprating\SearchBundle\SearchParams\SearchParams;

class ProductSearch
{
    /** @var  SearchEngineInterface */
    protected $searchEngine;

    public function __construct(SearchEngineInterface $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    /**
     * @param SearchParams $searchParams
     *
     * @return array|Product[]
     */
    public function find(SearchParams $searchParams)
    {
        return $this->searchEngine->search($searchParams->getString());
    }
}
