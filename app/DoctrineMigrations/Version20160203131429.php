<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160203131429 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, advantages VARCHAR(4000) DEFAULT NULL, disadvantages VARCHAR(4000) DEFAULT NULL, comment LONGTEXT NOT NULL, is_like TINYINT(1) DEFAULT NULL, full_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D22944584584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_3D0AE6DC5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_shop_price (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, shop_id INT DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, INDEX IDX_9EB927A04584665A (product_id), INDEX IDX_9EB927A04D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_AC6A4CA25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD manufacturer_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD advantages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', ADD disadvantages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('CREATE INDEX IDX_D34A04ADA23B42D ON product (manufacturer_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE product_shop_price');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP INDEX IDX_D34A04ADA23B42D ON product');
        $this->addSql('ALTER TABLE product DROP manufacturer_id, DROP description, DROP advantages, DROP disadvantages');
    }
}
