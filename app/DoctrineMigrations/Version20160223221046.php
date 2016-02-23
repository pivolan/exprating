<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223221046 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD is_published TINYINT(1) NOT NULL COMMENT \'Комментарий опубликован или ожидает решения модератора\', ADD published_at DATETIME NOT NULL COMMENT \'Дата публикации комментария\', CHANGE created_at created_at DATETIME NOT NULL COMMENT \'Дата создания комментария.\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP is_published, DROP published_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'Дата создания пользователя. Или дата его регистрации.\'');
    }
}
