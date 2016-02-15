<?php
/**
 * Date: 11.02.16
 * Time: 12:37
 */

namespace AppBundle\Tests\Form;

use AppBundle\Form\SortProductType;
use AppBundle\SortProduct\SortProduct;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\ConstraintViolationList;

class SortProductTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'fieldName' => SortProduct::FIELD_MIN_PRICE,
            'direction' => SortProduct::DIRECTION_DESC,
        ];

        $object = new SortProduct();
        $form = $this->factory->create(SortProductType::class, clone $object);

        $object->setFieldName(SortProduct::FIELD_MIN_PRICE);
        $object->setDirection(SortProduct::DIRECTION_DESC);

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