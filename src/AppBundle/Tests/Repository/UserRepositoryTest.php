<?php

/**
 * Date: 02.02.16
 * Time: 17:46.
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;

class UserRepositoryTest extends AbstractWebCaseTest
{
    public function testAdminsEmails()
    {
        $em = $this->em;
        $emails = $em->getRepository('AppBundle:User')->findEmails();
        $this->assertContains(['email'=>'category@exprating.lo'], $emails);
    }
}
