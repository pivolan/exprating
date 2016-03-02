<?php

/**
 * Date: 11.02.16
 * Time: 10:40.
 */

namespace Exprating\CharacteristicBundle\Tests\Form;

use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Form\CommonProductSearchType;

class CommonProductSearchTypeTest extends AbstractWebCaseTest
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'test_name',
            'priceGTE' => 100.25,
            'priceLTE' => 200.55,
            'characteristics' => [[
                                      'name' => 'test_filter_int',
                                      'valueGTE' => 100,
                                      'valueLTE' => 200,
                                  ],
                                  [
                                      'name' => 'test_filter_dec',
                                      'valueGTE' => 100.25,
                                      'valueLTE' => 200.36,
                                  ],
                                  [
                                      'name' => 'test_filter_str',
                                      'value' => 'this is a string',
                                  ],
            ],
        ];
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $em->persist((new Characteristic())->setName('test_filter_int')
            ->setSlug('test_filter_int')
            ->setType(Characteristic::TYPE_INT)
            ->setLabel('test_filter_label1'));
        $em->persist((new Characteristic())->setName('test_filter_dec')
            ->setSlug('test_filter_dec')
            ->setType(Characteristic::TYPE_DECIMAL)
            ->setLabel('test_filter_label2'));
        $em->persist((new Characteristic())->setName('test_filter_str')
            ->setSlug('test_filter_str')
            ->setType(Characteristic::TYPE_STRING)
            ->setLabel('test_filter_label3'));
        $em->flush();

        $object = new CommonProductSearch();
        $object->setName('test_name');
        $characteristicInt = (new CharacteristicSearchParameter())->setName('test_filter_int');
        $object->addCharacteristics($characteristicInt);
        $characteristicStr = (new CharacteristicSearchParameter())->setName('test_filter_str');
        $object->addCharacteristics($characteristicStr);
        $characteristicDec = (new CharacteristicSearchParameter())->setName('test_filter_dec');
        $object->addCharacteristics($characteristicDec);

        $form = $this->client->getContainer()->get('form.factory')->create(CommonProductSearchType::class, $object);

        $object2 = new CommonProductSearch();
        $object2->setName('test_name');
        $object2->setPriceGTE(100.25);
        $object2->setPriceLTE(200.55);
        $characteristicDec->setValueLTE(200.36)->setValueGTE(100.25)->setType(Characteristic::TYPE_DECIMAL);
        $characteristicInt->setValueLTE(200)->setValueGTE(100)->setType(Characteristic::TYPE_INT);
        $characteristicStr->setValue('this is a string')->setType(Characteristic::TYPE_STRING);
        $object2
            ->addCharacteristics($characteristicInt)
            ->addCharacteristics($characteristicStr)
            ->addCharacteristics($characteristicDec);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object2, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
