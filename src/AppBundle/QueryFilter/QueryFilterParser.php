<?php
/**
 * Date: 02.03.16
 * Time: 13:34
 */

namespace AppBundle\QueryFilter;


use AppBundle\ProductFilter\ProductFilter;
use Doctrine\ORM\EntityManager;

class QueryFilterParser
{
    /**
     * @var EntityManager
     */
    protected $_em;

    /**
     * QueryFilter constructor.
     *
     * @param EntityManager $_em
     */
    public function __construct(EntityManager $_em)
    {
        $this->_em = $_em;
    }

    /**
     * @param $string
     *
     * @return ProductFilter
     */
    public function parse($string)
    {
        return new ProductFilter();
    }
}