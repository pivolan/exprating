<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160228111152 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_people_group (category_id VARCHAR(255) NOT NULL COMMENT \'уникальное название на латинице. Используется для ссылки\', people_group_id VARCHAR(255) NOT NULL, INDEX IDX_7C741D1712469DE2 (category_id), INDEX IDX_7C741D178FA5F5D2 (people_group_id), PRIMARY KEY(category_id, people_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D1712469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D178FA5F5D2 FOREIGN KEY (people_group_id) REFERENCES people_group (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_people_group');
    }
}
