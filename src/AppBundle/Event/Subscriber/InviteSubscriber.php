<?php
/**
 * Date: 10.03.16
 * Time: 4:13
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\User;
use AppBundle\Event\Invite\InviteActivateEvent;
use AppBundle\Event\Invite\InviteApproveRightsEvent;
use AppBundle\Event\Invite\InviteEvents;
use AppBundle\Event\Invite\InviteRequestRightsEvent;
use AppBundle\Event\Invite\InviteSendEvent;
use AppBundle\Event\Invite\InviteCompleteRegistrationEvent;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

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
            InviteEvents::COMPLETE_REGISTRATION => [['inviteComplete', 0], ['inviteCompleteNotify', 0], ['flush']],
            InviteEvents::ACTIVATE              => [['inviteActivate', 0], ['inviteActivateNotify', 0], ['flush']],
            InviteEvents::SEND                  => [['inviteSend', 0], ['inviteSendNotify', 0], ['flush']],
            InviteEvents::APPROVE_RIGHTS        => [['approveRights', 0], ['approveRightsNotify', 0], ['flush']],
            InviteEvents::REQUEST_RIGHTS        => [['requestRights', 0], ['requestRightsNotify', 0], ['flush']],
        ];

    }

    public function approveRights(InviteApproveRightsEvent $event)
    {
        $expert = $event->getExpert();
        $expert->addRole(User::ROLE_EXPERT_CURATOR);
    }

    public function approveRightsNotify(InviteApproveRightsEvent $event)
    {

        $message = \Swift_Message::newInstance()
            ->setSubject('Вам предоставлены права Куратора пользователем '.$event->getCurator()->getUsername())
            ->setTo($event->getExpert()->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/approveRightsNotify.html.twig',
                    ['curator' => $event->getCurator(),]
                )
            );
        $this->mailer->send($message);
    }

    public function inviteActivate(
        InviteActivateEvent $event,
        $eventName,
        EventDispatcherInterface $dispatcher
    ) {
        $invite = $event->getInvite();
        $expert = new User();
        $expert->setEmail($invite->getEmail())
            ->setUsername($invite->getEmail())
            ->setPlainPassword(uniqid())
            ->setEnabled(true)
            ->setIsActivated(false)
            ->setEmailCanonical($invite->getEmail())
            ->setUsernameCanonical($invite->getEmail())
            ->addRole(User::ROLE_EXPERT);

        $invite->setExpert($expert)
            ->setIsActivated(true)
            ->setActivatedAt(new \DateTime());
        $this->em->persist($expert);
        $dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_COMPLETED,
            new FilterUserResponseEvent($expert, $event->getRequest(), $event->getResponse())
        );
    }

    public function inviteActivateNotify(InviteActivateEvent $event)
    {
        $invite = $event->getInvite();
        $curator = $invite->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваш инвайт был активирован пользователем '.$invite->getEmail())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/InviteActivateNotify.html.twig',
                    ['invite' => $invite,]
                )
            );
        $this->mailer->send($message);
        $message = \Swift_Message::newInstance()
            ->setSubject('Вы активировали инвайт, создан пользователь.')
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/InviteActivateNotifyExpert.html.twig',
                    ['invite' => $invite,]
                )
            );
        $this->mailer->send($message);
    }

    public function inviteComplete(InviteCompleteRegistrationEvent $event)
    {
        $user = $event->getExpert();
        $invite = $user->getInvite();
        $curator = $invite->getCurator();
        $user->setCurator($curator)
            ->setIsActivated(true);
        foreach ($curator->getCategories() as $category) {
            $user->addCategory($category);
        }
    }

    public function inviteCompleteNotify(InviteCompleteRegistrationEvent $event)
    {
        $invite = $event->getExpert()->getInvite();
        $curator = $invite->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('У вас появился новый эксперт '.$invite->getEmail())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/InviteCompleteNotifyToCurator.html.twig',
                    ['invite' => $invite,]
                )
            );
        $this->mailer->send($message);
        $message = \Swift_Message::newInstance()
            ->setSubject('Вы успешно прошли регистрацию ')
            ->setTo($invite->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/InviteCompleteNotifyToExpert.html.twig',
                    ['invite' => $invite,]
                )
            );
        $this->mailer->send($message);
    }

    public function inviteSend(InviteSendEvent $event)
    {
        $this->em->persist($event->getInvite());
    }

    public function inviteSendNotify(InviteSendEvent $event)
    {
        $invite = $event->getInvite();
        $curator = $invite->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject($curator->getFullName().' Пригласил вас стать экспертом на сайте')
            ->setTo($invite->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/InviteSendNotify.html.twig',
                    ['invite' => $invite,]
                )
            );
        $this->mailer->send($message);
    }

    public function requestRights(InviteRequestRightsEvent $event)
    {

    }

    public function requestRightsNotify(InviteRequestRightsEvent $event)
    {
        $expert = $event->getExpert();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject(
                sprintf('Пользователь %s хочет стать Куратором, чтобы рассылать приглашения', $expert->getUsername())
            )
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/requestRightsNotify.html.twig',
                    ['expert' => $expert,]
                )
            );
        $this->mailer->send($message);
    }

    public function flush($event)
    {
        $this->em->flush();
    }
}