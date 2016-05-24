<?php

/**
 * Date: 08.02.16
 * Time: 16:34.
 */

namespace Exprating\SearchBundle\ProductSearch;

use AppBundle\Entity\Product;
use Exprating\SearchBundle\Dto\SearchCriteria;
use Exprating\SearchBundle\Engine\EngineInterface as SearchEngineInterface;
use Exprating\SearchBundle\Dto\SearchParams;
use Exprating\SearchBundle\Sphinx\IndexNames;

class PartnerProductSearch
{
    /**
     * @var SearchEngineInterface
     */
    protected $searchEngine;

    public function __construct(SearchEngineInterface $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    /**
     * @param SearchParams $searchParams
     *
     * @param int          $limit
     * @param int          $offset
     *
     * @return \AppBundle\Entity\Product[]|array
     */
    public function find(SearchParams $searchParams, $limit = 0, $offset = 0)
    {
        return $this->searchEngine->search(
            $searchParams->getString(),
            (new SearchCriteria())->setIndexName(IndexNames::INDEX_PARTNER_PRODUCT)
                ->setRepositoryName('ExpratingImportXmlBundle:PartnerProduct')
                ->setFields(['name',])
                ->setCriteria(['available' => true])
                ->setLimit($limit)
                ->setOffset($offset)
        );
    }
}
