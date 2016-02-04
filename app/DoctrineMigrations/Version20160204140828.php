<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160204140828 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE characteristic (slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, `label` VARCHAR(255) NOT NULL, type_object VARCHAR(255) DEFAULT \'string\' NOT NULL, UNIQUE INDEX UNIQ_522FA9505E237E06 (name), PRIMARY KEY(slug)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_characteristic (product_id INT NOT NULL, characteristic_id VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, value_int INT DEFAULT NULL, value_decimal NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_146D77C4584665A (product_id), INDEX IDX_146D77CDEE9D12B (characteristic_id), PRIMARY KEY(product_id, characteristic_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_characteristic ADD CONSTRAINT FK_146D77C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_characteristic ADD CONSTRAINT FK_146D77CDEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristic (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_characteristic DROP FOREIGN KEY FK_146D77CDEE9D12B');
        $this->addSql('DROP TABLE characteristic');
        $this->addSql('DROP TABLE product_characteristic');
    }
}
