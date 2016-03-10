<?php
/**
 * Date: 10.03.16
 * Time: 4:13
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Event\Invite\InviteActivateEvent;
use AppBundle\Event\Invite\InviteApproveRightsEvent;
use AppBundle\Event\Invite\InviteEvents;
use AppBundle\Event\Invite\InviteRequestRightsEvent;
use AppBundle\Event\Invite\InviteSendEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InviteSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->twig = $twig;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            InviteEvents::ACTIVATE       => [['inviteActivate', 0], ['inviteActivateNotify', 0], ['flush']],
            InviteEvents::SEND           => [['inviteSend', 0], ['inviteSendNotify', 0], ['flush']],
            InviteEvents::APPROVE_RIGHTS => [['approveRights', 0], ['approveRightsNotify', 0], ['flush']],
            InviteEvents::REQUEST_RIGHTS => [['requestRights', 0], ['requestRightsNotify', 0], ['flush']],
        ];

    }

    public function approveRights(InviteApproveRightsEvent $event)
    {
    }

    public function approveRightsNotify(InviteApproveRightsEvent $event)
    {
    }

    public function inviteActivate(InviteActivateEvent $event)
    {
    }

    public function inviteActivateNotify(InviteActivateEvent $event)
    {
    }

    public function inviteSend(InviteSendEvent $event)
    {
    }

    public function inviteSendNotify(InviteSendEvent $event)
    {
    }

    public function requestRights(InviteRequestRightsEvent $event)
    {
    }

    public function requestRightsNotify(InviteRequestRightsEvent $event)
    {
    }

    public function flush($event)
    {
        $this->em->flush();
    }
}