<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160330234440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_175B7694733D5B5D ON registration_request');
        $this->addSql('CREATE INDEX IDX_7BE6DA39733D5B5D ON registration_request (curator_id)');
        $this->addSql('DROP INDEX idx_eacb58ea19878307 ON registration_request_category');
        $this->addSql('CREATE INDEX IDX_3C562ED37F45718C ON registration_request_category (registration_request_id)');
        $this->addSql('DROP INDEX idx_eacb58ea12469de2 ON registration_request_category');
        $this->addSql('CREATE INDEX IDX_3C562ED312469DE2 ON registration_request_category (category_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_7BE6DA39733D5B5D ON registration_request');
        $this->addSql('CREATE INDEX IDX_175B7694733D5B5D ON registration_request (curator_id)');
        $this->addSql('DROP INDEX idx_3c562ed37f45718c ON registration_request_category');
        $this->addSql('CREATE INDEX IDX_EACB58EA19878307 ON registration_request_category (registration_request_id)');
        $this->addSql('DROP INDEX idx_3c562ed312469de2 ON registration_request_category');
        $this->addSql('CREATE INDEX IDX_EACB58EA12469DE2 ON registration_request_category (category_id)');
    }
}
