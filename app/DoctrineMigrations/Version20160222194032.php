<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160222194032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings CHANGE rating2weight rating2weight INT DEFAULT 25 COMMENT \'Вес оценки №2\', CHANGE rating3weight rating3weight INT DEFAULT 25 COMMENT \'Вес оценки №3\', CHANGE rating4weight rating4weight INT DEFAULT 25 COMMENT \'Вес оценки №4\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings CHANGE rating2weight rating2weight INT DEFAULT 25 COMMENT \'Вес оценки №1\', CHANGE rating3weight rating3weight INT DEFAULT 25 COMMENT \'Вес оценки №1\', CHANGE rating4weight rating4weight INT DEFAULT 25 COMMENT \'Вес оценки №1\'');
    }
}
