<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212121907 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_93B15FD412469DE2');
        $this->addSql('DROP INDEX UNIQ_93B15FD412469DE2 ON rating_settings');
        $this->addSql('ALTER TABLE rating_settings DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rating_settings DROP id, CHANGE category_id category_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rating_settings ADD PRIMARY KEY (category_id)');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_BB78153212469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_BB78153212469DE2');
        $this->addSql('ALTER TABLE rating_settings DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rating_settings ADD id INT AUTO_INCREMENT NOT NULL PRIMARY KEY, CHANGE category_id category_id VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93B15FD412469DE2 ON rating_settings (category_id)');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_93B15FD412469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
    }
}
