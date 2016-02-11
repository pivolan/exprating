<?php
/**
 * Date: 11.02.16
 * Time: 11:14
 */

namespace Exprating\CharacteristicBundle\Tests\Form;

use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Form\CharacteristicSearchParameterType;
use Exprating\CharacteristicBundle\Form\CommonProductSearchType;
use Symfony\Component\Form\Test\TypeTestCase;

class CharacteristicSearchParameterTypeTest extends AbstractWebCaseTest
{
    const OBJECT_DATA = 'objectData';
    const FORM_DATA = 'formData';
    const TYPE = 'type';

    /**
     * @dataProvider getValidTestData
     */
    public function testSubmitValidDataInteger($data)
    {
        $formData = $data[self::FORM_DATA];
        $name = $formData['name'];
        $objectData = $data[self::OBJECT_DATA];

        $characteristic = new Characteristic();
        $characteristic->setName($name)
            ->setSlug($name)
            ->setType($data[self::TYPE])
            ->setLabel('test_filter_label');
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $em->persist($characteristic);
        $em->flush();


        $object = new CharacteristicSearchParameter();
        $object->setName($name);

        $form = $this->client->getContainer()->get('form.factory')->create(CharacteristicSearchParameterType::class, $object);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $object->setValueGTE($objectData['valueLTE'])->setValueLTE($objectData['valueGTE'])->setValue($objectData['value']);
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function getValidTestData()
    {
        return [
            [
                [self::FORM_DATA   => ['name' => 'test_filter_name_int', 'valueLTE' => 100, 'valueGTE' => 50],
                 self::OBJECT_DATA => ['valueLTE' => 100, 'valueGTE' => 50, 'value' => null],
                 self::TYPE        => Characteristic::TYPE_INT
                ],
                [self::FORM_DATA   => ['name' => 'test_filter_name_str', 'value' => 'olololo'],
                 self::OBJECT_DATA => ['valueLTE' => null, 'valueGTE' => null, 'value' => 'olololo'],
                 self::TYPE        => Characteristic::TYPE_STRING
                ],
                [self::FORM_DATA   => ['name' => 'test_filter_name_dec', 'valueLTE' => 100.45, 'valueGTE' => 50.89],
                 self::OBJECT_DATA => ['valueLTE' => 100.45, 'valueGTE' => 50.89, 'value' => null],
                 self::TYPE        => Characteristic::TYPE_DECIMAL
                ],
            ]
        ];
    }

} 