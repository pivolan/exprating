<?php
/**
 * Date: 04.02.16
 * Time: 12:50
 */

namespace Exprating\CharacteristicBundle\Tests\Entity;


use AppBundle\Tests\AbstractWebCaseTest;
use Exprating\CharacteristicBundle\Entity\Characteristic;

class CharacteristicTest extends AbstractWebCaseTest
{
    public function testChoiceType()
    {

        $em = $this->doctrine->getManager();

        $characteristic = new Characteristic();
        $characteristic->setType(Characteristic::TYPE_STRING)
            ->setSlug('size')
            ->setName('Size')
            ->setLabel('Size');

        $em->persist($characteristic);
        $characteristic = new Characteristic();
        $characteristic->setType('other')
            ->setName('other')
            ->setLabel('other')
            ->setSlug('other');

        $em->persist($characteristic);
        $em->flush();
    }
} 