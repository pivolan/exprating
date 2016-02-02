<?php

namespace Application\Migrations\Tests;

use AppBundle\Tests\Command\CommandTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;

class AllMigrationsTest extends CommandTestCase
{
    public function setUp()
    {
        parent::setUp();
        // удалим все из таблицы
        $this->dropTables();
    }

    public function tearDown()
    {
        // удалим все из таблицы
//        $this->dropTables();
        $this->clearTables();
        parent::tearDown();
    }

    public function testMigrations()
    {
        // зальем в таблицу начальные данные
        $this->importFromFile();
        $client = static::createClient();
        $output = $this->runCommand('doctrine:migrations:migrate --no-interaction');
        $this->assertContains('sql queries', $output, 'Миграция не удалась');
        // проверим что разницы между базой и схемой из кода нету
        $this->checkDiffTableAndSchema();

    }

    /**
     * проверим что разницы между базой и схемой из кода нету
     */
    protected function checkDiffTableAndSchema()
    {
        $client = static::createClient();
        $output = $this->runCommand('doctrine:schema:update --dump-sql');
        $this->assertContains('Nothing to update - your database is already in sync with the current entity metadata.', $output, 'В базе после миграций есть различия со схемой из кода');
    }

    protected function clearTables()
    {
        $client = static::createClient();
        /** @var $em EntityManager */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $tables = $em->getConnection()->getSchemaManager()->listTables();
        foreach ($tables as $table) {
            $em->getConnection()->executeUpdate('DELETE FROM ' . $table->getName());
            $em->getConnection()->executeQuery('ALTER TABLE ' . $table->getName() . ' AUTO_INCREMENT=1');
        }
    }

    protected function dropTables()
    {
        $client = static::createClient();
        $this->runCommand('doctrine:database:drop --force');
        $this->runCommand('doctrine:database:create');
    }

    protected function importFromFile()
    {
        $query = file_get_contents(
            __DIR__
            . DIRECTORY_SEPARATOR .
            'Files'
            . DIRECTORY_SEPARATOR .
            'database.sql'
        );
        $client = static::createClient();
        /** @var $em EntityManager */
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        $em->getConnection()->executeQuery($query);
    }
}
