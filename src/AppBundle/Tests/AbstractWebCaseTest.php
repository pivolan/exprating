<?php

namespace AppBundle\Tests;

use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebCaseTest extends WebTestCase
{
    /** @var  Registry */
    protected $doctrine;
    /** @var  EntityManager */
    protected $em;
    /** @var  Client */
    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = $client = static::createClient();
        $this->doctrine = $client->getContainer()->get('doctrine');
        $this->em = $this->doctrine->getManager();
        $this->doctrine->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        try {
            $this->doctrine->getConnection()->rollback();
        } catch (\Exception $e) {

        }
        parent::tearDown();
    }
}
