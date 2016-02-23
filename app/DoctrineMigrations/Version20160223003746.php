<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223003746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_admin_category (user_id INT NOT NULL, admin_category_id VARCHAR(255) NOT NULL COMMENT \'уникальное название на латинице. Используется для ссылки\', INDEX IDX_F969C19EA76ED395 (user_id), INDEX IDX_F969C19E8F5CD4EB (admin_category_id), PRIMARY KEY(user_id, admin_category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19E8F5CD4EB FOREIGN KEY (admin_category_id) REFERENCES category (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_admin_category');
    }
}
