<?php

/**
 * Date: 08.02.16
 * Time: 16:26.
 */

namespace Exprating\SearchBundle\Engine;

use AppBundle\Entity\Product;
use Exprating\SearchBundle\Dto\SearchCriteria;

interface EngineInterface
{
    /**
     * @param $string
     *
     * @return Product[]
     */
    public function search($string, SearchCriteria $searchCriteria);
}
