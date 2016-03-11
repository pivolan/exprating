<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311115447 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, expert_id INT DEFAULT NULL, curator_first_level_id INT DEFAULT NULL, curator_second_level_id INT DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL COMMENT \'IP адрес посетителя\', user_agent VARCHAR(255) DEFAULT NULL COMMENT \'user-agent информация о бразуере посетителя\', url VARCHAR(255) DEFAULT NULL COMMENT \'Полный адрес страницы включая схему и домен\', created_at DATETIME NOT NULL COMMENT \'Дата просмотра страницы\', INDEX IDX_437EE939A76ED395 (user_id), INDEX IDX_437EE9394584665A (product_id), INDEX IDX_437EE939C5568CE4 (expert_id), INDEX IDX_437EE9396EF1EF (curator_first_level_id), INDEX IDX_437EE939E8F09294 (curator_second_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9394584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939C5568CE4 FOREIGN KEY (expert_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE9396EF1EF FOREIGN KEY (curator_first_level_id) REFERENCES fos_user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE939E8F09294 FOREIGN KEY (curator_second_level_id) REFERENCES fos_user (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE visit');
    }
}
