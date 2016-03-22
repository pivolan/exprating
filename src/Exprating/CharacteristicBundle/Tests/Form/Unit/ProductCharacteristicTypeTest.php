<?php
/**
 * Date: 22.03.16
 * Time: 17:25
 */

namespace Exprating\CharacteristicBundle\Tests\Form\Unit;

use AppBundle\Entity\Product;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Exprating\CharacteristicBundle\Form\ProductCharacteristicType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;

class ProductCharacteristicTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $this->factory->create(ProductCharacteristicType::class);
        $characteristic = (new Characteristic())->setType(Characteristic::TYPE_STRING)->setSlug('qwerty');
        $productCharacteristic = (new ProductCharacteristic())->setCharacteristic($characteristic);
        $this->factory->create(ProductCharacteristicType::class, $productCharacteristic);
        $characteristic->setType(Characteristic::TYPE_DECIMAL);
        $productCharacteristic->setValue(10.23);
        $this->factory->create(ProductCharacteristicType::class, $productCharacteristic);

        $characteristic->setType('qwerty');
        try {
            $this->factory->create(ProductCharacteristicType::class, $productCharacteristic);
        } catch (CharacteristicTypeException $e) {
            $this->assertInstanceOf(CharacteristicTypeException::class, $e);
        }
        $this->assertTrue(isset($e), 'Должен быть exception на неверный тип характеристики');

        $characteristic->setType(Characteristic::TYPE_INT);
        $productCharacteristic->setValue(25);
        $this->factory->create(ProductCharacteristicType::class, $productCharacteristic);
        $form = $this->factory->create(ProductCharacteristicType::class);

        $formData = [
            'characteristic' => $characteristic,
            'value'          => 10,
            'headGroup'      => 'main',
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $productCharacteristic2 = $form->getData();
        $this->assertEquals(10, $productCharacteristic2->getValue());
    }
}