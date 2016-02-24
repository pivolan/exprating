<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224092945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE is_published is_published TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'Комментарий опубликован или ожидает решения модератора\', CHANGE published_at published_at DATETIME DEFAULT NULL COMMENT \'Дата публикации комментария\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment CHANGE is_published is_published TINYINT(1) NOT NULL COMMENT \'Комментарий опубликован или ожидает решения модератора\', CHANGE published_at published_at DATETIME NOT NULL COMMENT \'Дата публикации комментария\'');
    }
}
