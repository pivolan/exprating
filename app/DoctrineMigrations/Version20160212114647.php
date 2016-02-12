<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212114647 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_93B15FD412469DE2');
        $this->addSql('ALTER TABLE rating_settings ADD rating1weight INT DEFAULT NULL, ADD rating2weight INT DEFAULT NULL, ADD rating3weight INT DEFAULT NULL, ADD rating4weight INT DEFAULT NULL');
        $this->addSql('DROP INDEX uniq_93b15fd412469de2 ON rating_settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BB78153212469DE2 ON rating_settings (category_id)');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_93B15FD412469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_BB78153212469DE2');
        $this->addSql('ALTER TABLE rating_settings DROP rating1weight, DROP rating2weight, DROP rating3weight, DROP rating4weight');
        $this->addSql('DROP INDEX uniq_bb78153212469de2 ON rating_settings');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_93B15FD412469DE2 ON rating_settings (category_id)');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_BB78153212469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
    }
}
