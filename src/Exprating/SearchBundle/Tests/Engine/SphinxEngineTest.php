<?php

/**
 * Date: 08.02.16
 * Time: 21:44.
 */

namespace Exprating\SearchBundle\Tests\Engine;

use Exprating\SearchBundle\Dto\SearchCriteria;
use Exprating\SearchBundle\Engine\SphinxEngine;
use Exprating\SearchBundle\Sphinx\IndexNames;
use IAkumaI\SphinxsearchBundle\Search\Sphinxsearch;

class SphinxEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testSearch()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Sphinxsearch $sphinx */
        $sphinx = $this->getMockBuilder(Sphinxsearch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sphinx->expects($this->once())
            ->method('searchEx')
            ->will($this->returnValue(['matches' => [['entity' => 'value']]]));
        $engine = new SphinxEngine($sphinx);
        $this->assertEquals(
            ['value'],
            $engine->search('qwerty', (new SearchCriteria())->setIndexName(IndexNames::INDEX_PRODUCT))
        );
    }
}
