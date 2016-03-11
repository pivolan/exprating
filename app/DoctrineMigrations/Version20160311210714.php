<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311210714 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_edit_history ADD product_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_edit_history ADD CONSTRAINT FK_10E0E99A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_edit_history ADD CONSTRAINT FK_10E0E99AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_10E0E99A4584665A ON product_edit_history (product_id)');
        $this->addSql('CREATE INDEX IDX_10E0E99AA76ED395 ON product_edit_history (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_edit_history DROP FOREIGN KEY FK_10E0E99A4584665A');
        $this->addSql('ALTER TABLE product_edit_history DROP FOREIGN KEY FK_10E0E99AA76ED395');
        $this->addSql('DROP INDEX IDX_10E0E99A4584665A ON product_edit_history');
        $this->addSql('DROP INDEX IDX_10E0E99AA76ED395 ON product_edit_history');
        $this->addSql('ALTER TABLE product_edit_history DROP product_id, DROP user_id');
    }
}
