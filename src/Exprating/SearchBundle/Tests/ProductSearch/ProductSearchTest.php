<?php
/**
 * Date: 22.03.16
 * Time: 5:16
 */

namespace Exprating\SearchBundle\Tests\ProductSearch;


use Exprating\SearchBundle\Engine\EngineInterface;
use Exprating\SearchBundle\Engine\SqlEngine;
use Exprating\SearchBundle\ProductSearch\ProductSearch;
use Exprating\SearchBundle\Dto\SearchParams;

class ProductSearchTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        $engine = $this->getMockBuilder(EngineInterface::class)
            ->getMock();
        $engine->method('search')
            ->willReturn([]);
        $productSearch = new ProductSearch($engine);
        $this->assertEquals([], $productSearch->find(new SearchParams()));
    }
}