<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160208095030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_label CHANGE rating1 rating1label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rating_label CHANGE rating2 rating2label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rating_label CHANGE rating3 rating3label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rating_label CHANGE rating4 rating4label VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_label CHANGE rating1label rating1 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE rating_label CHANGE rating2label rating2 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE rating_label CHANGE rating3label rating3 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE rating_label CHANGE rating4label rating4 VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
