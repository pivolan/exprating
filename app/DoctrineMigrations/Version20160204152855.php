<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160204152855 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE characteristic (slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, `label` VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT \'string\' NOT NULL, scale VARCHAR(255) DEFAULT NULL, `head_group` VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_522FA9505E237E06 (name), PRIMARY KEY(slug)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_characteristic (id BIGINT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, characteristic_id VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, value_int INT DEFAULT NULL, value_decimal NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_146D77C4584665A (product_id), INDEX IDX_146D77CDEE9D12B (characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE characteristic');
        $this->addSql('DROP TABLE product_characteristic');
    }
}
