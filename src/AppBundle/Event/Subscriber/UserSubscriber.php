<?php
/**
 * Date: 10.03.16
 * Time: 4:13
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Event\User\ApproveCreateExpertEvent;
use AppBundle\Event\User\CreateExpertRequestEvent;
use AppBundle\Event\User\UserEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
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
            UserEvents::CREATE_EXPERT_REQUEST => [['createExpertRequest', 1], ['notifyRequest'], ['flush']],
            UserEvents::CREATE_EXPERT_APPROVE => [['createExpertApprove', 1], ['notifyApprove'], ['flush']],
        ];
    }

    public function createExpertRequest(CreateExpertRequestEvent $event)
    {
    }

    public function notifyRequest(CreateExpertRequestEvent $event)
    {
    }

    public function createExpertApprove(ApproveCreateExpertEvent $event)
    {
    }

    public function notifyApprove(ApproveCreateExpertEvent $event)
    {
    }

    public function flush($event)
    {
        $this->em->flush();
    }
}