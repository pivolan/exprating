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
     * @return array|Product[]
     */
    public function find(SearchParams $searchParams)
    {
        return $this->searchEngine->search(
            $searchParams->getString(),
            (new SearchCriteria())->setIndexName(IndexNames::INDEX_PARTNER_PRODUCT)
                ->setRepositoryName('ExpratingImportXmlBundle:PartnerProduct')
                ->setFields(['name',])
                ->setCriteria(['available' => true])
        );
    }
}
