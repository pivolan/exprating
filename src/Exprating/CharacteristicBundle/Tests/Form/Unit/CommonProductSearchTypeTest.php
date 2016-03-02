<?php

/**
 * Date: 11.02.16
 * Time: 10:40.
 */

namespace Exprating\CharacteristicBundle\Tests\Form\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Form\CommonProductSearchType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommonProductSearchTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name'            => 'test_name',
            'priceGTE'        => 100.25,
            'priceLTE'        => 200.55,
            'characteristics' => new ArrayCollection(),
        ];

        $form = $this->factory->create(CommonProductSearchType::class);

        $object = new CommonProductSearch();
        $object->setName('test_name');
        $object->setPriceGTE(100.25);
        $object->setPriceLTE(200.55);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
