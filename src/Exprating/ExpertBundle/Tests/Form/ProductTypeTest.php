<?php
/**
 * Date: 11.02.16
 * Time: 13:19
 */

namespace Exprating\ExpertBundle\Tests\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\RatingSettings;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Form\CommonProductSearchType;
use Exprating\ExpertBundle\Form\ProductType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductTypeTest extends AbstractWebCaseTest
{
    /**
     * @dataProvider getValidTestData
     */
    public function testSubmitValidData($data)
    {
        $category = new Category();
        $category->setSlug($data['categorySlug'])
            ->setName($data['categorySlug']);
        $ratingSettings = new RatingSettings();
        $ratingSettings->setCategory($category)
            ->setRating1Label($data['label1'])
            ->setRating2Label($data['label2'])
            ->setRating3Label($data['label3'])
            ->setRating4Label($data['label4']);
        $category->setRatingSettings($ratingSettings);
        $product = new Product();
        $product->setCategory($category)
            ->setName($data['product']['name'])
            ->setMinPrice($data['product']['minPrice']);

        foreach ($data['characteristics'] as $name => $characteristicData) {
            $characteristic = new Characteristic();
            $characteristic->setName($name)
                ->setType($characteristicData['type'])
                ->setLabel($characteristicData['label'])
                ->setSlug($name)
                ->setScale($characteristicData['scale']);
            $productCharacteristic = new ProductCharacteristic();
            $productCharacteristic->setProduct($product)
                ->setCharacteristic($characteristic);
            $product->addProductCharacteristic($productCharacteristic);
        }
        $product2 = clone $product;

        $form = $this->client->getContainer()->get('form.factory')->create(ProductType::class, clone $product2);

        // submit the data to the form directly
        $form->submit($data['formData']);

        $this->assertTrue($form->isSynchronized());

        foreach ($product->getProductCharacteristics() as $characteristic) {
            $characteristic->setValue($data['characteristics'][$characteristic->getCharacteristic()->getSlug()]);
        }
        $product
            ->setRating1($data['formData']['rating1'])
            ->setRating2($data['formData']['rating2'])
            ->setRating3($data['formData']['rating3'])
            ->setRating4($data['formData']['rating4'])
            ->setAdvantages($data['formData']['advantages'])
            ->setDisadvantages($data['formData']['disadvantages'])
            ->setExpertOpinion($data['formData']['expertOpinion'])
            ->setExpertComment($data['formData']['expertComment']);
        $this->assertEquals($product, $form->getData());
        $this->assertEquals($product->getProductCharacteristics(), $form->getData()->getProductCharacteristics());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data['formData']) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidTestData()
    {
        return
            [[
                 ['categorySlug'    => 'category_slug',
                  'label1'          => 'label_1',
                  'label2'          => 'label_2',
                  'label3'          => 'label_3',
                  'label4'          => 'label_4',
                  'product'         => ['name' => 'product_name_test', 'minPrice' => 78.45],
                  'characteristics' => [
                      'c_name_1' => ['value' => 100, 'type' => Characteristic::TYPE_INT, 'label' => 'c_name_label_1', 'scale' => 'kg'],
                      'c_name_2' => ['value' => 'u', 'type' => Characteristic::TYPE_STRING, 'label' => 'c_name_label_2', 'scale' => null],
                      'c_name_3' => ['value' => 2.58, 'type' => Characteristic::TYPE_DECIMAL, 'label' => 'c_name_label_3', 'scale' => 'cm'],
                  ],
                  'formData'        => ['rating1'                => 11,
                                        'rating2'                => 22,
                                        'rating3'                => 33,
                                        'rating4'                => 44,
                                        'advantages'             => ['good', 'very good'],
                                        'disadvantages'          => ['bad', 'very bad'],
                                        'expertOpinion'          => 'opinion of expert',
                                        'expertComment'          => 'comment of expert',
                                        'productCharacteristics' => [
                                            'c_name_1' => ['valueInt' => 1001],
                                            'c_name_2' => ['valueString' => 'u'],
                                            'c_name_3' => ['valueDecimal' => 2.58],
                                        ],
                  ]
                 ],
             ]];
    }
}