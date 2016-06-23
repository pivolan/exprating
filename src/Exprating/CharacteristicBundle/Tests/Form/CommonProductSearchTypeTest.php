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
            'name'            => 'test_name',
            'priceGTE'        => 100.25,
            'priceLTE'        => 200.55,
            'characteristics' => [
                [
                    'name'     => 'test_filter_int',
                    'valueGTE' => 100,
                    'valueLTE' => 200,
                ],
                [
                    'name'     => 'test_filter_dec',
                    'valueGTE' => 100.25,
                    'valueLTE' => 200.36,
                ],
                [
                    'name'  => 'test_filter_str',
                    'value' => 'this is a string',
                ],
            ],
        ];
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $em->persist(
            (new Characteristic())->setName('test_filter_int')
                ->setSlug('test_filter_int')
                ->setType(Characteristic::TYPE_INT)
                ->setLabel('test_filter_label1')
        );
        $em->persist(
            (new Characteristic())->setName('test_filter_dec')
                ->setSlug('test_filter_dec')
                ->setType(Characteristic::TYPE_DECIMAL)
                ->setLabel('test_filter_label2')
        );
        $em->persist(
            (new Characteristic())->setName('test_filter_str')
                ->setSlug('test_filter_str')
                ->setType(Characteristic::TYPE_STRING)
                ->setLabel('test_filter_label3')
        );
        $em->flush();

        $commonProductSearch = new CommonProductSearch();
        $commonProductSearch->setName('test_name');
        $characteristicInt = (new CharacteristicSearchParameter())->setName('test_filter_int');
        $commonProductSearch->addCharacteristics($characteristicInt);
        $characteristicStr = (new CharacteristicSearchParameter())->setName('test_filter_str');
        $commonProductSearch->addCharacteristics($characteristicStr);
        $characteristicDec = (new CharacteristicSearchParameter())->setName('test_filter_dec');
        $commonProductSearch->addCharacteristics($characteristicDec);

        $form = $this->client->getContainer()->get('form.factory')->create(
            CommonProductSearchType::class,
            $commonProductSearch
        );

        $commonProductSearch2 = new CommonProductSearch();
        $commonProductSearch2->setName('test_name');
        $commonProductSearch2->setPriceGTE(100.25);
        $commonProductSearch2->setPriceLTE(200.55);
        $characteristicDec->setValueLTE(200.36)->setValueGTE(100.25)->setType(Characteristic::TYPE_DECIMAL);
        $characteristicInt->setValueLTE(200)->setValueGTE(100)->setType(Characteristic::TYPE_INT);
        $characteristicStr->setValue('this is a string')->setType(Characteristic::TYPE_STRING);
        $commonProductSearch2
            ->addCharacteristics($characteristicInt)
            ->addCharacteristics($characteristicStr)
            ->addCharacteristics($characteristicDec);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($commonProductSearch2, $form->getData());

        $commonProductSearch2->setCharacteristics([]);
        $this->assertEquals([], $commonProductSearch2->getCharacteristics());

        $commonProductSearch->removeCharacteristics($characteristicDec);
        $this->assertCount(2, $commonProductSearch->getCharacteristics());


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
