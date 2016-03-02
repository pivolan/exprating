<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160303010835 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE min_price min_price NUMERIC(10, 2) DEFAULT NULL COMMENT \'Минимальная найденная цена на товар среди всех продавцов, Ставится во время импорта товаров\', CHANGE rating rating INT DEFAULT NULL COMMENT \'Общий рейтинг товара. Автоматически считается на основе других оценок.\', CHANGE rating1 rating1 INT DEFAULT NULL COMMENT \'Оценка №1, описание, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating2 rating2 INT DEFAULT NULL COMMENT \'Оценка №2, описание, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating3 rating3 INT DEFAULT NULL COMMENT \'Оценка №3, описание, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating4 rating4 INT DEFAULT NULL COMMENT \'Оценка №4, описание, зависит от Категории и прописано здесь RatingSettings\', CHANGE is_enabled is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'Будет ли товар отображаться на сайте. Означает что на товар готово экспертное мнение\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product CHANGE min_price min_price NUMERIC(10, 2) DEFAULT NULL COMMENT \'Минимальная найденная цена на товар, среди всех продавцов. Ставится во время импорта товаров\', CHANGE rating rating INT DEFAULT NULL COMMENT \'Общий рейтинг товара. Автоматически ставится после создания обзора, на основе других оценок.\', CHANGE rating1 rating1 INT DEFAULT NULL COMMENT \'Оценка №1, описание за что оценка, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating2 rating2 INT DEFAULT NULL COMMENT \'Оценка №2, описание за что оценка, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating3 rating3 INT DEFAULT NULL COMMENT \'Оценка №3, описание за что оценка, зависит от Категории и прописано здесь RatingSettings\', CHANGE rating4 rating4 INT DEFAULT NULL COMMENT \'Оценка №4, описание за что оценка, зависит от Категории и прописано здесь RatingSettings\', CHANGE is_enabled is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL COMMENT \'Будет ли товар отображаться на сайте. Означает что на товар готово экспертное мнение и оно одобрена модератором.\'');
    }
}
