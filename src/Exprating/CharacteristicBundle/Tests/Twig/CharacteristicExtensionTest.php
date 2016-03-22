<?php
/**
 * Date: 22.03.16
 * Time: 5:29
 */

namespace Exprating\CharacteristicBundle\Tests\Twig;


use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Twig\CharacteristicExtension;

class CharacteristicExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testProductCharacteristics()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twig->method('render')
            ->willReturnCallback(
                function ($template, $args) {
                    return $args['characteristics'];
                }
            );
        $characteristicExtension = new CharacteristicExtension($twig);
        $this->assertEquals('characteristics_extension', $characteristicExtension->getName());
        $this->assertCount(1, $characteristicExtension->getFunctions());

        $characteristic = (new Characteristic())
            ->setLabel('label')
            ->setSlug('char_1')
            ->setName('char 1')
            ->setType(Characteristic::TYPE_STRING);
        $productCharacteristic = (new ProductCharacteristic())
            ->setHeadGroup('main')
            ->setCharacteristic($characteristic)
            ->setValue('main_asd');
        $productCharacteristic2 = (new ProductCharacteristic())
            ->setHeadGroup('second')
            ->setCharacteristic($characteristic)
            ->setValue('second_value');
        $productCharacteristics = [$productCharacteristic, $productCharacteristic2, $productCharacteristic2];
        $result = $characteristicExtension->productCharacteristics($productCharacteristics);
        $this->assertEquals(
            [
                'main'   => [['label', 'main_asd', null]],
                'second' => [
                    ['label', 'second_value', null],
                    ['label', 'second_value', null],
                ],
            ],
            $result
        );
    }
}