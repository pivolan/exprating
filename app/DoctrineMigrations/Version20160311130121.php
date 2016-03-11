<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311130121 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE create_expert_request_category (create_expert_request_id INT NOT NULL, category_id VARCHAR(255) NOT NULL COMMENT \'уникальное название на латинице. Используется для ссылки\', INDEX IDX_EACB58EA19878307 (create_expert_request_id), INDEX IDX_EACB58EA12469DE2 (category_id), PRIMARY KEY(create_expert_request_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE create_expert_request_category ADD CONSTRAINT FK_EACB58EA19878307 FOREIGN KEY (create_expert_request_id) REFERENCES create_expert_request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE create_expert_request_category ADD CONSTRAINT FK_EACB58EA12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE create_expert_request ADD curator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE create_expert_request ADD CONSTRAINT FK_175B7694733D5B5D FOREIGN KEY (curator_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_175B7694733D5B5D ON create_expert_request (curator_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE create_expert_request_category');
        $this->addSql('ALTER TABLE create_expert_request DROP FOREIGN KEY FK_175B7694733D5B5D');
        $this->addSql('DROP INDEX IDX_175B7694733D5B5D ON create_expert_request');
        $this->addSql('ALTER TABLE create_expert_request DROP curator_id');
    }
}
