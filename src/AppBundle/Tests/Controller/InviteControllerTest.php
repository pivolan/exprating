<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;

class InviteControllerTest extends AbstractWebCaseTest
{
    public function testInviteGet()
    {
        $client = $this->client;

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

        $client->request(
            'GET',
            '/invite',
            [],
            []
        );
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testInvitePost()
    {
        $client = $this->client;

        $client->request(
            'POST',
            '/invite',
            ['invite[email]' => 'qwerty@qwerty.ru'],
            [],
            [
                'PHP_AUTH_USER' => 'curator',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );
        $this->assertEquals(302, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $client->followRedirect();

        $this->assertNotContains('invite[email]', $client->getResponse()->getContent());
        $this->assertContains('Приглашенеие успешно отправлено', $client->getResponse()->getContent());
    }

    public function testInviteActivate()
    {
        $invite = new Invite();
        $invite->setEmail('lolo@olol.ru');
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $em->persist($invite);
        $em->flush();

        $client = $this->client;
        $client->request('GET', '/invite/'.$invite->getHash());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertContains('user[plainPassword]', $client->getResponse()->getContent());
        $this->assertContains('user[username]', $client->getResponse()->getContent());
        $this->assertContains('user[email]', $client->getResponse()->getContent());
        $this->assertContains('user[skype]', $client->getResponse()->getContent());
        $this->assertContains('user[phone]', $client->getResponse()->getContent());
    }

    public function testCompleteRegistration()
    {
        $client = $this->client;
        $faker = $client->getContainer()->get('exprating_faker.faker.fake_entities_generator');
        $em = $this->em;
        $user = $faker->user();
        $em->persist($user);
        $em->flush();
        $client->request(
            'POST',
            '/invite/register/complete',
            [
                'user[plainPassword]'       => 'qwerty',
                'user[plainPasswordRepeat]' => 'qwerty',
                'user[username]'            => 'qwerty',
                'user[email]'               => 'qwe@qwe.ru',
                'user[phone]'               => '321654987',
                'user[skype]'               => 'asdfg',
            ],
            [],
            [
                'PHP_AUTH_USER' => $user->getUsername(),
                'PHP_AUTH_PW'   => $user->getPlainPassword(),
            ]
        );
        $this->assertContains('Ваш аккаунт успешно активирован', $client->getResponse()->getContent());
        /** @var User $updatedUser */
        $updatedUser = $em->getRepository('AppBundle:User')->findOneBy(['username' => 'qwerty']);
        $this->assertTrue($updatedUser->getIsActivated());
        $this->assertEquals($user->getEmail(), $updatedUser->getEmail());
        $this->assertEquals('asdfg', $updatedUser->getSkype());
        $this->assertEquals('321654987', $updatedUser->getPhone());
    }

    public function testInviteRights()
    {

    }

    public function testApproveRights()
    {

    }
}
