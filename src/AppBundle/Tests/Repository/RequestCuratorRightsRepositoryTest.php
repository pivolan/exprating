<?php
/**
 * Date: 31.03.16
 * Time: 1:29
 */

namespace AppBundle\Tests\Repository;


use AppBundle\Entity\User;
use AppBundle\Tests\AbstractWebCaseTest;
use AppBundle\Entity\RequestCuratorRights;

class RequestCuratorRightsRepositoryTest extends AbstractWebCaseTest
{
    public function testFindLastByPeriod()
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'expert']);
        $createdAt = [];
        foreach ([1, 6, 8] as $dayBefore) {
            $createdAt[$dayBefore] = new \DateTime("-$dayBefore days");
            $this->em->persist(
                (new RequestCuratorRights())
                    ->setExpert($user)
                    ->setCurator($user->getCurator())
                    ->setCreatedAt($createdAt[$dayBefore])
            );
        }

        $this->em->persist(
            (new RequestCuratorRights())
                ->setExpert($user)
                ->setCurator($user->getCurator())
                ->setCreatedAt(new \DateTime('-8 days'))
        );
        $this->em->flush();

        $requestCuratorRightsList = $this->em->getRepository('AppBundle:RequestCuratorRights')->findLastByPeriod(
            $user,
            7
        );
        $this->assertCount(2, $requestCuratorRightsList);
        $this->assertEquals($createdAt[1], $requestCuratorRightsList[0]->getCreatedAt());
        $this->assertEquals($createdAt[6], $requestCuratorRightsList[1]->getCreatedAt());
    }
}