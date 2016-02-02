<?php

namespace AppBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

abstract class AbstractWebCaseTest extends WebTestCase
{
    /** @var  Registry */
    protected $docrine;

    public function setUp()
    {
        parent::setUp();
        $client          = static::createClient();
        $this->docrine   = $client->getContainer()->get('doctrine');
//        $this->docrine->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
//        $this->docrine->getConnection()->rollback();
        parent::tearDown();
    }
}
