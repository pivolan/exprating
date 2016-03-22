<?php
/**
 * Date: 22.03.16
 * Time: 17:13
 */

namespace Exprating\CharacteristicBundle\Tests\Repository;


use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Repository\CharacteristicRepository;

class CharacteristicRepositoryTest extends AbstractWebCaseTest
{
    public function testGetIdNameByQ()
    {
        $characteristic = (new Characteristic())
            ->setName('asdfghj')
            ->setSlug('asdfghjk')
            ->setLabel('qwerty')
            ->setType(Characteristic::TYPE_STRING);
        $this->em->persist($characteristic);
        $this->em->flush();
        $results = $this->em->getRepository('CharacteristicBundle:Characteristic')->getIdNameByQ('dfg', 10, 0);
        $this->assertCount(1, $results);
        $this->assertEquals($characteristic->getSlug(), $results[0]['id']);
        $this->assertEquals($characteristic->getName(), $results[0]['text']);
    }
}