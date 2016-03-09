<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160309122404 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_BB78153212469DE2');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_BB78153212469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC112469DE2');
        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC1A76ED395');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC112469DE2 FOREIGN KEY (category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC1A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_admin_category DROP FOREIGN KEY FK_F969C19E8F5CD4EB');
        $this->addSql('ALTER TABLE user_admin_category DROP FOREIGN KEY FK_F969C19EA76ED395');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19E8F5CD4EB FOREIGN KEY (admin_category_id) REFERENCES category (slug) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE rating_settings DROP FOREIGN KEY FK_BB78153212469DE2');
        $this->addSql('ALTER TABLE rating_settings ADD CONSTRAINT FK_BB78153212469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE user_admin_category DROP FOREIGN KEY FK_F969C19EA76ED395');
        $this->addSql('ALTER TABLE user_admin_category DROP FOREIGN KEY FK_F969C19E8F5CD4EB');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19EA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_admin_category ADD CONSTRAINT FK_F969C19E8F5CD4EB FOREIGN KEY (admin_category_id) REFERENCES category (slug)');
        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC1A76ED395');
        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC112469DE2');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC1A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC112469DE2 FOREIGN KEY (category_id) REFERENCES category (slug)');
    }
}
