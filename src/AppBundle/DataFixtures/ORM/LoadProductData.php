<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductShopPrice;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadProductData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        srand(1);
        $user = $this->getReference(LoadUserData::REFERENCE_EXPERT_USER);
        for ($i = 1; $i <= 150; $i++) {
            $categoryKey = $i % 5;
            $category = $this->getReference("category_$categoryKey");
            $manufacturerKey = $i % 10;
            $manufacturer = $this->getReference(LoadManufacturerData::REFERENCE_MANUFACTURER . $manufacturerKey);
            $product = new Product();
            $isEnabled = $i % 3 != 0;
            $product->setName('title_' . $i)
                ->setMinPrice(rand(1.00, 1000.00))
                ->setRating(rand(1, 99))
                ->setRating1(rand(1, 99))
                ->setRating2(rand(1, 99))
                ->setRating3(rand(1, 99))
                ->setRating4(rand(1, 99))
                ->setIsEnabled($isEnabled)
                ->setVisitsCount(rand(0, 100))
                ->setEnabledAt(new \DateTime())
                ->setSlug('product_' . $i)
                ->setCategory($category)
                ->setManufacturer($manufacturer)
                ->setExpertUser(($isEnabled || rand(0,1)) ? $user : null)
                ->setExpertComment('У нас такая же модель, только с мешком для сбора пыли. Выбрали мешковой, т.к. в нем мощность немного выше. Пылесосом очень довольны. Все функции выполняет на 5 с плюсом! Работает тихо и качественно.')
                ->setPreviewImage('http://placehold.it/280x250')
                ->setDisadvantages([
                    'Невысокая мощность всасывания',
                    'Нет управления мощностью на рукоятке',
                    'Относительно высокая стоимость',
                    'Хорошая маневренность',
                    'Глянец на боковинах может быстро потерять лоск из-за контакта с мебелью и дверными проемами',
                ])
                ->setAdvantages([
                    'Очень низкий уровень шума',
                    'Удобный в использовании и обслуживании пылесборник',
                    'Высокая эффективность очистки ковра',
                    'Хорошая маневренность',
                    '6 насадок, включая турбо-щетку',
                ])
                ->setExpertOpinion('
                        <h3>Справка</h3>
                        <p>Модель Bosch BGS5ZOOO1 произведена в Китае. Это пылесос с пылесборником-контейнером. Заявленная потребляемая мощность составляет 1800 Вт, заявленная мощность всасывания — 300 Вт. Масса устройства в сборе (включая шланг, насадку, пылесборник) — 9,9 килограмма. Модель оснащена фильтром тонкой очистки (HEPA), комплектуется 6 насадками, включая турбо-щетку, универсальную насадку для пола и ковра, щелевую и для чистки мягкой мебели.</p>
                        <h3>Впечатления</h3>
                        <p>Конструктивно основной блок модели похож на тот, что применяется в Bosch BGS 52530 – лишь цвет отделки другой. Однако, наполнение корпусов разное. То ли в силу своей конструкции, то ли за счет улучшенной шумоизоляции, но блок BGS5ZOOO1 имеет более низкий КПД (соотношение мощности всасывания и потребляемой мощности), зато даже на максимальной скорости шумит значительно слабее. По итогам инструментальных измерений – первое место в июньском тесте по шуму с итогом 71,1 дБ(А). В испытании на очистку коврового покрытия от загрязнителей данная модель также заняла лидирующие позиции, уступив только пылесосу марки Dyson, да и то – лишь при сборе металлических шайб. Имитация шерсти домашних животных и оба вида нитей были убраны с ковра полностью. Если учесть, что измеренная мощность всасывания у Bosch BGS5ZOOO1 одна из самых низких в тесте (менее 300 Вт), то благодарить за высокую эффективность аппарата следует грамотно сконструированную турбо-щетку, которая входит в комплект поставки. Измеренная потребляемая мощность совпала с заявленной и составила 1,8 кВт.  Специфический внешний вид и эргономика обезличенного образца не вызвали у фокус-группы серьезных нареканий, хотя и высоких оценок респонденты не поставили. Недостатком исследовали признали большие габариты и массу модели, но отметили удобство извлечения и опустошения контейнера. Поставленную в упрек родственной модели Bosch BGS 52530 малую длину кабеля (6,1м) испытатели не подтвердили: сетевой провод BGS5ZOOO1 разматывается на длину до 7,6м. Не хотелось бы думать, что данное различие обусловлено лишь позиционированием устройств на иерархической лестнице в каталоге Bosch.   А вот неудобный регулятор мощности без световой индикации у BGS5ZOOO1 точно такой же, как и у более доступной модели (если так вообще можно сказать о пылесосе ценой 18 000 рублей). Тесты на маневренность подтвердили хороший потенциал аппарата, несмотря на его внушительные габариты: из обеих испытательных ловушек он выбрался без каких-либо проблем.</p>
                        <h3>Полезные ссылки</h3>
                        <p>Результаты измерений приведены в протоколе (см. ниже) и в сравнительном тесте пылесосов. Описание методики проведения испытаний опубликовано в отдельной статье.</p>
                    ');
            $shopsCount = $i % 5;
            for ($shopIndex = 0; $shopIndex <= $shopsCount; $shopIndex++) {
                $shopKey = $i % 45 + $shopIndex;
                $shop = $this->getReference(LoadShopData::REFERENCE_SHOP . $shopKey);
                $productShopPrice = new ProductShopPrice();
                $productShopPrice->setPrice($product->getMinPrice() + $shopIndex * (0.02 * $product->getMinPrice()))
                    ->setProduct($product)
                    ->setShop($shop);
                $manager->persist($productShopPrice);
            }
            $manager->persist($product);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadUserData',
                'AppBundle\DataFixtures\ORM\LoadCategoryData',
                'AppBundle\DataFixtures\ORM\LoadShopData',
                'AppBundle\DataFixtures\ORM\LoadManufacturerData',];
    }
}