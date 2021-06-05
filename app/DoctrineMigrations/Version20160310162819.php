<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160310162819 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE create_expert_request (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invite (hash CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', curator_id INT DEFAULT NULL, expert_id INT DEFAULT NULL, created_at DATETIME NOT NULL, isActivated TINYINT(1) NOT NULL, is_from_feedback TINYINT(1) NOT NULL, activated_at DATETIME NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_C7E210D7733D5B5D (curator_id), UNIQUE INDEX UNIQ_C7E210D7C5568CE4 (expert_id), PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edit_history (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, diff LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (category_id VARCHAR(255) NOT NULL COMMENT \'уникальное название на латинице. Используется для ссылки\', title VARCHAR(255) DEFAULT NULL, h1 VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, PRIMARY KEY(category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user ADD skype VARCHAR(255) DEFAULT NULL COMMENT \'Skype аккаунт пользователя\', ADD phone VARCHAR(255) DEFAULT NULL COMMENT \'Номер телефона пользователя\', ADD is_activated TINYINT(1) DEFAULT \'1\' NOT NULL COMMENT \'Эксперт активирован: прошел по инвайту, заполнил форму.\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE create_expert_request');
        $this->addSql('DROP TABLE invite');
        $this->addSql('DROP TABLE product_edit_history');
        $this->addSql('DROP TABLE seo');
        $this->addSql('ALTER TABLE fos_user DROP skype, DROP phone, DROP is_activated');
    }
}
