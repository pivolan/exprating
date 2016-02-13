<?php
/**
 * Date: 11.02.16
 * Time: 12:37
 */

namespace AppBundle\Tests\Form;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'fullName' => 'full name',
            'message' => 'some message',
        ];

        $form = $this->factory->create(CommentType::class, new Comment());

        $object = new Comment();
        $object->setFullName('full name');
        $object->setMessage('some message');

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