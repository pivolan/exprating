<?php

/**
 * Date: 04.02.16
 * Time: 12:50.
 */

namespace Exprating\CharacteristicBundle\Tests\Entity;

use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;

class ProductCharacteristicTest extends \PHPUnit_Framework_TestCase
{
    public function testValueType()
    {
        $characteristic = new Characteristic();
        $characteristic->setType(Characteristic::TYPE_STRING);

        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setCharacteristic($characteristic)
            ->setValue('string_text');

        $this->assertEquals('string_text', $productCharacteristic->getValue());
        $this->assertEquals('string_text', $productCharacteristic->getValueString());
        $this->assertNull($productCharacteristic->getValueInt());
        $this->assertNull($productCharacteristic->getValueDecimal());

        $characteristic->setType(Characteristic::TYPE_INT);
        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setCharacteristic($characteristic)
            ->setValue('2564');

        $this->assertEquals('2564', $productCharacteristic->getValue());
        $this->assertFalse(2564 === $productCharacteristic->getValue());
        $this->assertEquals(2564, $productCharacteristic->getValueInt());
        $this->assertNull($productCharacteristic->getValueString());
        $this->assertNull($productCharacteristic->getValueDecimal());

        $characteristic->setType(Characteristic::TYPE_DECIMAL);
        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setCharacteristic($characteristic)
            ->setValue(123456.78);

        $this->assertEquals('123456.78', $productCharacteristic->getValue());
        $this->assertFalse(123456.78 === $productCharacteristic->getValue());
        $this->assertEquals(123456.78, $productCharacteristic->getValueDecimal());
        $this->assertEquals('123456.78', $productCharacteristic->getValueDecimal());
        $this->assertNull($productCharacteristic->getValueString());
        $this->assertNull($productCharacteristic->getValueInt());

        $characteristic->setType('other');
        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setCharacteristic($characteristic);

        try {
            $productCharacteristic->setValue(123456.78);
            $this->assertTrue(false, 'No exception throw');
        } catch (CharacteristicTypeException $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Для характеристики товара использован несуществующий тип значения: other'
            );
        }

        try {
            $productCharacteristic->getValue();
            $this->assertTrue(false, 'No exception throw');
        } catch (CharacteristicTypeException $e) {
            $this->assertEquals(
                $e->getMessage(),
                'Для характеристики товара использован несуществующий тип значения: other'
            );
        }
    }
}
