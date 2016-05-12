<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160512050515 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE partner_product (hash VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, name VARCHAR(1000) NOT NULL, price INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, oldPrice INT DEFAULT NULL, category_id INT DEFAULT NULL, category_name VARCHAR(255) DEFAULT NULL, category_path VARCHAR(4000) DEFAULT NULL, market_category VARCHAR(4000) DEFAULT NULL, amount INT DEFAULT NULL, available TINYINT(1) NOT NULL, params LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', pictures LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', year INT DEFAULT NULL, vendor VARCHAR(255) DEFAULT NULL, vendor_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX id_company_unique (id, company), PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE offer');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE offer (hash VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, company VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(1000) NOT NULL COLLATE utf8_unicode_ci, price INT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, oldPrice INT DEFAULT NULL, category_id INT DEFAULT NULL, category_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, category_path VARCHAR(4000) DEFAULT NULL COLLATE utf8_unicode_ci, market_category VARCHAR(4000) DEFAULT NULL COLLATE utf8_unicode_ci, amount INT DEFAULT NULL, available TINYINT(1) NOT NULL, params LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', pictures LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:json_array)\', year INT DEFAULT NULL, vendor VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, vendor_code VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX id_company_unique (id, company), PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE partner_product');
    }
}
