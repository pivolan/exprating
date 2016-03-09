<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160309123233 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_characteristic DROP FOREIGN KEY FK_B33FEA6D12469DE2');
        $this->addSql('ALTER TABLE category_characteristic DROP FOREIGN KEY FK_B33FEA6DDEE9D12B');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6DDEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristic (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_people_group DROP FOREIGN KEY FK_7C741D1712469DE2');
        $this->addSql('ALTER TABLE category_people_group DROP FOREIGN KEY FK_7C741D178FA5F5D2');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D1712469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D178FA5F5D2 FOREIGN KEY (people_group_id) REFERENCES people_group (slug) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_characteristic DROP FOREIGN KEY FK_B33FEA6D12469DE2');
        $this->addSql('ALTER TABLE category_characteristic DROP FOREIGN KEY FK_B33FEA6DDEE9D12B');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE category_characteristic ADD CONSTRAINT FK_B33FEA6DDEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristic (slug)');
        $this->addSql('ALTER TABLE category_people_group DROP FOREIGN KEY FK_7C741D1712469DE2');
        $this->addSql('ALTER TABLE category_people_group DROP FOREIGN KEY FK_7C741D178FA5F5D2');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D1712469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE category_people_group ADD CONSTRAINT FK_7C741D178FA5F5D2 FOREIGN KEY (people_group_id) REFERENCES people_group (slug)');
    }
}
