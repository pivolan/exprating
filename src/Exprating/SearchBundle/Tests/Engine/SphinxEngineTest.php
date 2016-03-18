<?php

/**
 * Date: 08.02.16
 * Time: 21:44.
 */

namespace Exprating\SearchBundle\Tests\Engine;

use Exprating\SearchBundle\Engine\SphinxEngine;
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
        $this->assertEquals(['value'], $engine->search('qwerty'));
    }
}
