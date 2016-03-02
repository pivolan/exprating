<?php

namespace AppBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebCaseTest extends WebTestCase
{
    /** @var  Registry */
    protected $doctrine;
    /** @var  Client */
    protected $client;

    public function setUp()
    {
        // do setup tasks
        parent::setUp();
        $this->client = $client = static::createClient();
        $this->doctrine = $client->getContainer()->get('doctrine');
        $this->doctrine->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        // do setup tasks
        $this->doctrine->getConnection()->rollback();
        parent::tearDown();
    }
}
