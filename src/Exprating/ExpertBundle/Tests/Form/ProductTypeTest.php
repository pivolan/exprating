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
        $em = $this->doctrine->getManager();
        $category = new Category();
        $category->setSlug($data['categorySlug'])
            ->setName($data['categorySlug']);
        $em->persist($category);
        $ratingSettings = new RatingSettings();
        $ratingSettings->setCategory($category)
            ->setRating1Label($data['label1'])
            ->setRating2Label($data['label2'])
            ->setRating3Label($data['label3'])
            ->setRating4Label($data['label4']);
        $em->persist($ratingSettings);
        $category->setRatingSettings($ratingSettings);
        $product = new Product();
        $product->setCategory($category)
            ->setSlug('wrwer')
            ->setName($data['product']['name'])
            ->setMinPrice($data['product']['minPrice']);
        $em->persist($product);
        foreach ($data['characteristics'] as $name => $characteristicData) {
            $characteristic = new Characteristic();
            $characteristic->setName($name)
                ->setType($characteristicData['type'])
                ->setLabel($characteristicData['label'])
                ->setSlug($name)
                ->setScale($characteristicData['scale']);
            $productCharacteristic = new ProductCharacteristic();
            $productCharacteristic->setProduct($product)
                ->setCharacteristic($characteristic)
                ->setValue($characteristicData['value']);
            $product->addProductCharacteristic($productCharacteristic);
            $em->persist($characteristic);
            $em->persist($productCharacteristic);
        }
        foreach ($data['new_characteristics'] as $name => $characteristicData) {
            $characteristic = new Characteristic();
            $characteristic->setName($name)
                ->setType($characteristicData['type'])
                ->setLabel($characteristicData['label'])
                ->setSlug($name)
                ->setScale($characteristicData['scale']);
            $em->persist($characteristic);
        }

        $form = $this->client->getContainer()->get('form.factory')->create(ProductType::class, $product);

        // submit the data to the form directly
        $formData = $data['formData'];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());


        $this->assertEquals($formData['rating1'], $product->getRating1());
        $this->assertEquals($formData['rating2'], $product->getRating2());
        $this->assertEquals($formData['rating3'], $product->getRating3());
        $this->assertEquals($formData['rating4'], $product->getRating4());

        $this->assertEquals($formData['advantages'], $product->getAdvantages());
        $this->assertEquals($formData['disadvantages'], $product->getDisadvantages());
        $this->assertEquals($formData['expertOpinion'], $product->getExpertOpinion());
        $this->assertEquals($formData['expertComment'], $product->getExpertComment());

        $this->assertEquals(4, $product->getProductCharacteristics()->count());
        foreach($product->getProductCharacteristics() as $productCharacteristic){
            $this->assertContains($productCharacteristic->getValue(), [1001, 'u', 2.58, 3.58]);
        }

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidTestData()
    {
        return
            [[
                 ['categorySlug'       => 'category_slug',
                  'label1'             => 'label_1',
                  'label2'             => 'label_2',
                  'label3'             => 'label_3',
                  'label4'             => 'label_4',
                  'product'            => ['name' => 'product_name_test', 'minPrice' => 78.45],
                  'characteristics'    => [
                      ['value' => 100, 'type' => Characteristic::TYPE_INT, 'label' => 'c_name_label_1', 'scale' => 'kg'],
                      ['value' => 'u', 'type' => Characteristic::TYPE_STRING, 'label' => 'c_name_label_2', 'scale' => null],
                      ['value' => 2.58, 'type' => Characteristic::TYPE_DECIMAL, 'label' => 'c_name_label_3', 'scale' => 'cm'],
                  ],
                  'new_characteristics' => [
                      ['type' => Characteristic::TYPE_DECIMAL, 'slug' => 'qwerty', 'label' => 'c_name_label_4', 'scale' => 'cm']
                  ],
                  'formData'           => ['rating1'                => 11,
                                           'rating2'                => 22,
                                           'rating3'                => 33,
                                           'rating4'                => 44,
                                           'advantages'             => ['good', 'very good'],
                                           'disadvantages'          => ['bad', 'very bad'],
                                           'expertOpinion'          => 'opinion of expert',
                                           'expertComment'          => 'comment of expert',
                                           'productCharacteristics' => [
                                               ['value' => 1001],
                                               ['value' => 'u'],
                                               ['value' => 2.58],
                                               ['value' => 3.58, 'characteristic' => 'qwerty'],
                                           ],
                  ]
                 ],
             ]];
    }
}