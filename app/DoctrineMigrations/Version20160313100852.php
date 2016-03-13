<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160313100852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user ADD tree_root INT DEFAULT NULL, ADD lft INT NOT NULL COMMENT \'Поле формируется автоматически для дерева.\', ADD lvl INT NOT NULL COMMENT \'Уровень вложенности внутри дерева. Формируется автоматически.\', ADD rgt INT NOT NULL COMMENT \'Полу формируется автоматически для дерева.\'');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479A977936C FOREIGN KEY (tree_root) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_957A6479A977936C ON fos_user (tree_root)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479A977936C');
        $this->addSql('DROP INDEX IDX_957A6479A977936C ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP tree_root, DROP lft, DROP lvl, DROP rgt');
    }
}
