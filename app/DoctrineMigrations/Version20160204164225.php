<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160204164225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_characteristic (category_id VARCHAR(255) NOT NULL, characteristic_id VARCHAR(255) NOT NULL, INDEX IDX_B33FEA6D12469DE2 (category_id), INDEX IDX_B33FEA6DDEE9D12B (characteristic_id), PRIMARY KEY(category_id, characteristic_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6DDEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristic (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_characteristic');
    }
}
