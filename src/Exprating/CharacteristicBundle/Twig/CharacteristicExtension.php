<?php

/**
 * Date: 04.02.16
 * Time: 18:07.
 */

namespace Exprating\CharacteristicBundle\Twig;

use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;

class CharacteristicExtension extends \Twig_Extension
{
    /** @var  \Twig_Environment */
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('productCharacteristics', [$this, 'productCharacteristics']),
        ];
    }

    /**
     * @param ProductCharacteristic[] $productCharacteristics
     *
     * @return string
     */
    public function productCharacteristics($productCharacteristics)
    {
        $result = [];
        foreach ($productCharacteristics as $productCharacteristic) {
            $characteristic = $productCharacteristic->getCharacteristic();
            $result[$characteristic->getGroup()][] = [
                $characteristic->getLabel(),
                $productCharacteristic->getValue(),
                $characteristic->getScale(),
            ];
        }

        return $this->twig->render(
            'CharacteristicBundle:Extensions:productCharacteristics.html.twig',
            ['characteristics' => array_reverse($result)]
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'characteristics_extension';
    }
}
