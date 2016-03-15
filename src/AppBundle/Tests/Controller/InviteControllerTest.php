<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InviteControllerTest extends AbstractWebCaseTest
{
    public function testInviteGet()
    {
        $client = $this->client;
        $client->request(
            'GET',
            '/invite',
            [],
            []
        );
        $this->assertTrue($client->getResponse()->isRedirect());

        $client->request(
            'GET',
            '/invite',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'curator',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('invite[email]', $client->getResponse()->getContent());

        $client->request(
            'GET',
            '/invite',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'expert',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );
        $this->assertContains('/request/rights/invite', $client->getResponse()->getContent());
    }

    public function testInvitePost()
    {
        $client = $this->client;

        $crawler = $client->request(
            'POST',
            '/invite',
            ['invite' => ['email' => 'qwerty@qwerty.ru']],
            [],
            [
                'PHP_AUTH_USER' => 'curator',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );

        $this->assertContains('invite[email]', $client->getResponse()->getContent());
        $this->assertContains('Приглашение успешно отправлено', $crawler->filter('.content')->html());
    }

    public function testInviteActivate()
    {
        $invite = new Invite();
        $invite->setEmail('lolo@olobl.ru')
            ->setCurator($this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']));
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $em->persist($invite);
        $em->flush();

        $client = $this->client;
        $client->request('GET', '/invite/'.$invite->getHash());
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }
}
