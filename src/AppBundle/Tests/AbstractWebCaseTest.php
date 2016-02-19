<?php

namespace AppBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

abstract class AbstractWebCaseTest extends WebTestCase
{
    /** @var  Registry */
    protected $doctrine;
    /** @var  Client */
    protected $client;

    public function setUp()
    {
        if (!$this->hasDependencies()) {
            // do setup tasks
            parent::setUp();
            $this->client = $client = static::createClient();
            $this->doctrine = $client->getContainer()->get('doctrine');
            $this->doctrine->getConnection()->beginTransaction();
        }
    }

    public function tearDown()
    {
        if (!$this->hasDependencies()) {
            // do setup tasks
            $this->doctrine->getConnection()->rollback();
            parent::tearDown();
        }
    }
}