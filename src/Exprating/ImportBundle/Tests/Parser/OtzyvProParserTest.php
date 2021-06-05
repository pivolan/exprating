<?php
/**
 * Date: 29.03.16
 * Time: 15:42
 */

namespace Exprating\ImportBundle\Tests\Parser;


use Exprating\ImportBundle\Parser\OtzyvProParser;
use PHPHtmlParser\CurlInterface;

class OtzyvProParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $html
     *
     * @dataProvider getHtml
     */
    public function testParse($html, $htmlChild)
    {
        $curl = $this->createMock(CurlInterface::class);
        $curl->expects($this->at(0))->method('get')->willReturn($htmlChild);
        $parser = new OtzyvProParser($curl);
        $result = $parser->parse($html);
        $this->assertCount(2, $result);
        $this->assertCount(1, $result[0]['children']);
        $this->assertCount(2, $result[0]['children'][0]['children']);
    }

    public function getHtml()
    {
        return [
            [
                '<div class="blockboxcat">
	<div class="blockboxcat_left"><ul><li class="list">
										<span class="imgcat"><img src="/templates/otzyvy/images/icon_cat/cat_01.png" alt="Отзыв про"></span>
										<a href="/category/avto-i-moto/" class="href" title="">Авто и мото</a>
										<a href="#" title="" class="hoverbox_btn"></a>
										<div class="sub_category"><a href="/category/avto-i-moto/avtozapchasti/" title="">Автозапчасти</a><a href="/category/avto-i-moto/avtohimiya-i-masla/" title="">Автохимия и масла</a><a href="/category/avto-i-moto/aksessuaryi-dlya-avto/" title="">Аксессуары для авто</a><a href="/category/avto-i-moto/vodnyiy-transport/" title="">Водный транспорт</a><a href="/category/avto-i-moto/gruzovyie-avtomobili/" title="">Грузовые автомобили</a><a href="/category/avto-i-moto/doma-na-kolesah/" title="">Дома на колесах</a><a href="/category/avto-i-moto/legkovyie-avto/" title="">Легковые авто</a><a href="/category/avto-i-moto/moto/" title="">Мото</a><a href="/category/avto-i-moto/raznoe_auto_moto/" title="">Разное (авто и мото)</a><a href="/category/avto-i-moto/shinyi-i-diski/" title="">Шины и диски</a><a href="/category/avto-i-moto/elektronika-dlya-avto/" title="">Электроника для авто</a></div>
									</li><li class="list">
										<span class="imgcat"><img src="/templates/otzyvy/images/icon_cat/cat_02.png" alt="Отзыв про"></span>
										<a href="/category/organizatsii/" class="href" title="">Организации</a>
										<a href="#" title="" class="hoverbox_btn"></a>
										<div class="sub_category"><a href="/category/organizatsii/avtosalonyi-i-avtoryinki/" title="">Автосалоны и авторынки</a><a href="/category/organizatsii/avtoservisyi/" title="">Автосервисы</a><a href="/category/organizatsii/avtostoyanki-i-parkingi/" title="">Автостоянки и паркинги</a><a href="/category/organizatsii/avtoshkolyi/" title="">Автошколы</a><a href="/category/organizatsii/agentstva-nedvijimosti/" title="">Агентства недвижимости</a><a href="/category/organizatsii/apteki/" title="">Аптеки</a><a href="/category/organizatsii/bazaryi-ryinki-yarmarki/" title="">Базары, рынки, ярмарки</a><a href="/category/organizatsii/bani-i-saunyi/" title="">Бани и сауны</a><a href="/category/organizatsii/banki/" title="">Банки</a><a href="/category/organizatsii/bolnitsyi-i-kliniki/" title="">Больницы и клиники</a><a href="/category/organizatsii/veterinarnyie-kliniki-i-apteki/" title="">Ветеринарные клиники и аптеки</a><a href="/category/organizatsii/gostinitsyi-i-priyutyi-domashnih-jivotnyih/" title="">Гостиницы и приюты домашних животных</a><a href="/category/organizatsii/zooparki-delfinarii-okeanariumyi/" title="">Зоопарки, дельфинарии, океанариумы</a><a href="/category/organizatsii/kinoteatryi/" title="">Кинотеатры</a><a href="/category/organizatsii/klubyi-po-interesam/" title="">Клубы по интересам</a><a href="/category/organizatsii/kompanii-i-predpriyatiya/" title="">Компании и предприятия</a><a href="/category/organizatsii/magazinyi-i-torgovyie-tsentryi/" title="">Магазины и торговые центры</a><a href="/category/organizatsii/muzei-i-vyistavki/" title="">Музеи и выставки</a><a href="/category/organizatsii/muzyikalnyie-shkolyi/" title="">Музыкальные школы</a><a href="/category/organizatsii/obrazovanie/" title="">Образование</a><a href="/category/organizatsii/obschestvennyiy-transport-i-gruzoperevozki/" title="">Общественный транспорт и грузоперевозки</a><a href="/category/organizatsii/parki-attraktsionov/" title="">Парки аттракционов</a><a href="/category/organizatsii/parki-kulturyi-i-otdyiha/" title="">Парки культуры и отдыха</a><a href="/category/organizatsii/raznoe_city/" title="">Разное (в городе)</a><a href="/category/organizatsii/restoranyi-baryi-kafe/" title="">Рестораны, бары, кафе</a><a href="/category/organizatsii/salonyi-krasotyi/" title="">Салоны красоты</a><a href="/category/organizatsii/stadionyi-i-sportivnyie-klubyi/" title="">Стадионы и спортивные клубы</a><a href="/category/organizatsii/teatryi/" title="">Театры</a><a href="/category/organizatsii/torgovo-razvlekatelnyie-tsentryi/" title="">Торгово-развлекательные центры</a><a href="/category/organizatsii/tsirki/" title="">Цирки</a><a href="/category/organizatsii/cherniy-spisok/" title="">Черный список</a><a href="/category/organizatsii/zhilye-kompleksy/" title="">Жилые комплексы (ЖК)</a><a href="/category/organizatsii/kottedzhnye-poselki/" title="">Коттеджные поселки</a></div>
									</li></ul></div>
	<div class="clear"></div>
</div>',
                '<div class="category_page">
    <div class="cat_columbox">
        <div class="catmenu">
            <div class="tit_menu"><a class="list" href="http://otzyv.pro/category/avto-i-moto/avtozapchasti/"
                                     title="База отзывов о производителях запчастей">Автозапчасти</a></div>
            <ol class="submenu">
                <li id="list_20">
                    <div><a class="list" href="http://otzyv.pro/category/avto-i-moto/avtozapchasti/avtosvet-i-optika/">Автосвет
                        и оптика<span>41</span></a></div>
                </li>
                <li id="list_21">
                    <div><a class="list"
                            href="http://otzyv.pro/category/avto-i-moto/avtozapchasti/akkumulyatornyie-batarei/">Аккумуляторные
                        батареи<span>17</span></a></div>
                </li>
            </ol>
        </div>
    </div>
    <div class="clear"></div>
</div>',
            ],
        ];
    }
}