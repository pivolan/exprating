<?php

/**
 * Date: 19.02.16
 * Time: 19:24.
 */

namespace AppBundle\Tests\Event;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\Invite\Exception\RequestInviteRightsException;
use AppBundle\Event\Invite\InviteActivateEvent;
use AppBundle\Event\Invite\InviteEvents;
use AppBundle\Event\Invite\InviteRequestRightsEvent;
use AppBundle\Event\Invite\InviteSendEvent;
use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductRejectEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Event\ProductReservationOverEvent;
use AppBundle\Event\Invite\InviteCompleteRegistrationEvent;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChainEventTest
 * @package AppBundle\Tests\Event
 */
class InviteEventTest extends AbstractWebCaseTest
{
    public function testSend()
    {
        $container = $this->client->getContainer();
        $faker = $container->get('exprating_faker.faker.fake_entities_generator');
        $invite = new Invite();
        $curator = $faker->user()->setIsActivated(true)->addRole(User::ROLE_EXPERT_CURATOR);
        $invite->setEmail('qwerty@qwerty.ru')
            ->setCurator($curator);
        $this->em->persist($invite);
        $this->em->persist($curator);
        $container->get('event_dispatcher')->dispatch(InviteEvents::SEND, new InviteSendEvent($invite));
        $this->assertNotNull(
            $this->em->getRepository('AppBundle:User')->findOneBy(['username' => $curator->getUsername()])
        );
        $this->assertNotNull($this->em->getRepository('AppBundle:Invite')->find($invite->getHash()));
    }

    public function testActivate()
    {
        $invite = new Invite();
        $invite->setEmail('email@email.com')
            ->setCurator($this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']));
        $this->em->persist($invite);
        $this->em->flush();

        $container = $this->client->getContainer();
        $container->get('event_dispatcher')->dispatch(
            InviteEvents::ACTIVATE,
            new InviteActivateEvent($invite)
        );
        $user = $invite->getExpert();
        $this->assertNotNull($user);
        $this->assertEquals('email@email.com', $user->getUsername());
        $this->assertFalse($user->getIsActivated());
        $this->assertTrue($invite->getIsActivated());
        $this->assertNotNull($invite->getActivatedAt());
        $this->assertEquals($user, $invite->getExpert());
        $this->assertNull($user->getCurator());
    }

    public function testInviteCompleteRegistration()
    {
        $expert = $this->client->getContainer()->get('exprating_faker.faker.fake_entities_generator')->user();
        $expert->addRole(User::ROLE_EXPERT);
        $this->assertFalse($expert->getIsActivated());
        $invite = new Invite();
        $curator = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']);
        $invite->setEmail($expert->getEmail())
            ->setExpert($expert)
            ->setCurator($curator);
        $expert->setInvite($invite);
        $this->em->persist($expert);
        $this->em->persist($invite);
        $this->em->flush();

        $container = $this->client->getContainer();
        $container->get('event_dispatcher')->dispatch(
            InviteEvents::COMPLETE_REGISTRATION,
            new InviteCompleteRegistrationEvent($expert, new Request(), new Response())
        );
        $this->assertTrue($expert->getIsActivated());
        $this->assertEquals($expert->getCurator(), $invite->getCurator());
    }

    public function testInviteRights()
    {
        $expert = $this->client->getContainer()->get('exprating_faker.faker.fake_entities_generator')->user();
        $expert->addRole(User::ROLE_EXPERT);
        $curator = $this->em->getRepository('AppBundle:User')->findOneBy(['username' => 'curator']);
        $expert->setCurator($curator);
        $this->em->persist($expert);
        $this->em->flush();

        $this->client->getContainer()->get('event_dispatcher')->dispatch(
            InviteEvents::REQUEST_RIGHTS,
            new InviteRequestRightsEvent($expert)
        );
        $requestCuratorRightsList = $this->em->getRepository('AppBundle:RequestCuratorRights')->findLastByPeriod(
            $expert,
            1
        );
        $this->assertCount(1, $requestCuratorRightsList);
        try {
            $this->client->getContainer()->get('event_dispatcher')->dispatch(
                InviteEvents::REQUEST_RIGHTS,
                new InviteRequestRightsEvent($expert)
            );
            $this->assertFalse(true, 'Must be an exception here');
        } catch (\Exception $e) {
            $this->assertInstanceOf(RequestInviteRightsException::class, $e);
        }
    }
}
