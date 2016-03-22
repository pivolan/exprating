<?php
/**
 * Date: 22.03.16
 * Time: 5:06
 */

namespace Exprating\SearchBundle\Tests\DependencyInjection;


class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigTreeBuilder()
    {
        $configuration = new \Exprating\SearchBundle\DependencyInjection\Configuration();
        $this->assertInstanceOf(
            \Symfony\Component\Config\Definition\Builder\TreeBuilder::class,
            $configuration->getConfigTreeBuilder()
        );
    }
}