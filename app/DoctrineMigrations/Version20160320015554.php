<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160320015554 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_characteristic DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE category_characteristic ADD id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL, ADD head_group VARCHAR(255) DEFAULT NULL, ADD order_index INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX category_characteristic ON category_characteristic (category_id, characteristic_id)');
        $this->addSql('ALTER TABLE characteristic DROP head_group');
        $this->addSql('ALTER TABLE product_characteristic ADD head_group VARCHAR(255) DEFAULT NULL, ADD order_index INT NOT NULL COMMENT \'Порядок\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX category_characteristic ON category_characteristic');
        $this->addSql('ALTER TABLE category_characteristic DROP id');
        $this->addSql('ALTER TABLE category_characteristic DROP head_group, DROP order_index');
        $this->addSql('ALTER TABLE category_characteristic ADD PRIMARY KEY (category_id, characteristic_id)');
        $this->addSql('ALTER TABLE characteristic ADD head_group VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'Группа к которой принадлежит характеристика. Используется при отображении характеристик на странице товара.\'');
        $this->addSql('ALTER TABLE product_characteristic DROP head_group, DROP order_index');
    }
}
