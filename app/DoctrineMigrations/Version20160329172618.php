<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160329172618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_people_group');
        $this->addSql('DROP TABLE people_group');
        $this->addSql('DROP TABLE product_people_group');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_people_group (category_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci COMMENT \'уникальное название на латинице. Используется для ссылки\', people_group_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_7C741D1712469DE2 (category_id), INDEX IDX_7C741D178FA5F5D2 (people_group_id), PRIMARY KEY(category_id, people_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE people_group (slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_A6A33E855E237E06 (name), PRIMARY KEY(slug)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_people_group (product_id INT NOT NULL, people_group_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_2B071CEE4584665A (product_id), INDEX IDX_2B071CEE8FA5F5D2 (people_group_id), PRIMARY KEY(product_id, people_group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }
}
