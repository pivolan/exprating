<?php
/**
 * Date: 22.03.16
 * Time: 5:08
 */

namespace Exprating\SearchBundle\Tests\DependencyInjection;


use Exprating\SearchBundle\DependencyInjection\ExpratingSearchExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExpratingSearchExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {

        $expratingSearchExtension = new ExpratingSearchExtension();
        $containerBuilder = $this->getMockBuilder(ContainerBuilder::class)->getMock();
        $expratingSearchExtension->load(['exprating_search' => ['engine' => 'sql']], $containerBuilder);
    }
}