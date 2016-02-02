<?php
/**
 * Date: 02.02.16
 * Time: 13:20
 */

namespace AppBundle\Tests;


use AppBundle\Tests\Command\CommandTestCase;

class FixturesLoadTest extends CommandTestCase
{
    public function testFixtures()
    {
        $output = $this->runCommand('doctrine:fixtures:load --no-interaction');
        $this->assertNotContains('error', $output);
        $this->assertNotContains('Exception', $output);
    }
} 