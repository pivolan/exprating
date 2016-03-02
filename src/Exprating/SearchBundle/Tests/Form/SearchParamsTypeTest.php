<?php

/**
 * Date: 11.02.16
 * Time: 10:36.
 */

namespace Exprating\SearchBundle\Tests\Form;

use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\SearchParams\SearchParams;
use Symfony\Component\Form\Test\TypeTestCase;

class SearchParamsTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'string' => 'test_string',
        ];

        $form = $this->factory->create(SearchParamsType::class);

        $object = new SearchParams();
        $object->setString('test_string');

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
