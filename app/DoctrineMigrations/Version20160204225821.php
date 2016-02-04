<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160204225821 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rating_label (id INT AUTO_INCREMENT NOT NULL, category_id VARCHAR(255) DEFAULT NULL, rating1 VARCHAR(255) NOT NULL, rating2 VARCHAR(255) NOT NULL, rating3 VARCHAR(255) NOT NULL, rating4 VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_93B15FD412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating_label ADD CONSTRAINT FK_93B15FD412469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE product ADD rating1 INT DEFAULT NULL, ADD rating2 INT DEFAULT NULL, ADD rating3 INT DEFAULT NULL, ADD rating4 INT DEFAULT NULL, CHANGE rating rating INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rating_label');
        $this->addSql('ALTER TABLE product DROP rating1, DROP rating2, DROP rating3, DROP rating4, CHANGE rating rating INT NOT NULL');
    }
}
