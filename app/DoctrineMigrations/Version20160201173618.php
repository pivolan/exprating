<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160201173618 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("INSERT INTO category (slug, tree_root, parent_id, name, lft, lvl, rgt) VALUES
('elektronika',	'elektronika',	NULL,	'Электроника',	1,	0,	8),
('mebel-interer',	'mebel-interer',	NULL,	'Мебель интерьер',	1,	0,	32),
('sportinventar',	'sportinventar',	NULL,	'Спортинвентарь',	1,	0,	2),
('tovary-dlya-detey',	'tovary-dlya-detey',	NULL,	'Товары для детей',	1,	0,	2),
('bytovaya-elektronika',	'bytovaya-elektronika',	NULL,	'Бытовая электроника',	1,	0,	2),
('kosmetika-parfyumeriya',	'kosmetika-parfyumeriya',	NULL,	'Косметика, парфюмерия',	1,	0,	10),
('kompyutery',	'elektronika',	'elektronika',	'Компьютеры',	6,	1,	7),
('elektronika-i-foto',	'elektronika',	'elektronika',	'Электроника и фото ',	2,	1,	3),
('telefony-i-aksessuary',	'elektronika',	'elektronika',	'Телефоны и аксессуары ',	4,	1,	5),
('parfyumeriya',	'kosmetika-parfyumeriya',	'kosmetika-parfyumeriya',	'Парфюмерия',	6,	1,	7),
('sredstva-gigieny',	'kosmetika-parfyumeriya',	'kosmetika-parfyumeriya',	'Средства гигиены',	8,	1,	9),
('dekorativnaya-kosmetika',	'kosmetika-parfyumeriya',	'kosmetika-parfyumeriya',	'Декоративная косметика ',	4,	1,	5),
('sredstva-po-uhodu-za-kozhey-i-volosami',	'kosmetika-parfyumeriya',	'kosmetika-parfyumeriya',	'Средства по уходу за кожей и волосами ',	2,	1,	3),
('matrasy',	'mebel-interer',	'mebel-interer',	'Матрасы',	10,	1,	11),
('furnitura',	'mebel-interer',	'mebel-interer',	'Фурнитура',	24,	1,	25),
('raskladushki',	'mebel-interer',	'mebel-interer',	'Раскладушки',	20,	1,	21),
('myagkaya-mebel',	'mebel-interer',	'mebel-interer',	'Мягкая мебель',	12,	1,	13),
('naduvnaya-mebel',	'mebel-interer',	'mebel-interer',	'Надувная мебель',	14,	1,	15),
('pletenaya-mebel',	'mebel-interer',	'mebel-interer',	'Плетеная мебель',	18,	1,	19),
('postery-kartiny',	'mebel-interer',	'mebel-interer',	'Постеры, картины',	26,	1,	27),
('korpusnaya-mebel',	'mebel-interer',	'mebel-interer',	'Корпусная мебель',	8,	1,	9),
('gotovye-komplekty',	'mebel-interer',	'mebel-interer',	'Готовые комплекты ',	4,	1,	5),
('specialnaya-mebel',	'mebel-interer',	'mebel-interer',	'Специальная мебель',	22,	1,	23),
('tovary-dlya-dachi',	'mebel-interer',	'mebel-interer',	'Товары для дачи',	28,	1,	29),
('mebel-dlya-malyshey',	'mebel-interer',	'mebel-interer',	'Мебель для малышей ',	2,	1,	3),
('tovary-dlya-doma-i-remonta',	'mebel-interer',	'mebel-interer',	'Товары для дома и ремонта',	30,	1,	31),
('kompyuternye-stoly-stulya-i-kresla',	'mebel-interer',	'mebel-interer',	'Компьютерные столы, стулья и кресла',	6,	1,	7),
('osnovaniya-dlya-matrasov-i-namatrasniki',	'mebel-interer',	'mebel-interer',	'Основания для матрасов и наматрасникиь',	16,	1,	17);
");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("TRUNCATE TABLE category");
    }
}
