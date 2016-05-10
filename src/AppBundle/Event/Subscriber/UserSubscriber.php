<?php
/**
 * Date: 10.03.16
 * Time: 4:13
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Event\Invite\InviteEvents;
use AppBundle\Event\Invite\InviteSendEvent;
use AppBundle\Event\User\ApproveRegistrationEvent;
use AppBundle\Event\User\RegistrationRequestEvent;
use AppBundle\Event\User\UserEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
            UserEvents::REGISTRATION_REQUEST => [['registrationRequest', 1], ['notifyRequest'], ['flush']],
            UserEvents::REGISTRATION_APPROVE => [['createExpertApprove', 1], ['flush']],
        ];
    }

    public function registrationRequest(RegistrationRequestEvent $event)
    {
        $entity = $event->getRegistrationRequest();
        $this->em->persist($entity);
        /** @var User $categoryAdmin */
        $categoryAdmin = $this->em->getRepository('AppBundle:User')->getRandomByCategories($entity->getCategories());
        if (!$categoryAdmin) {
            throw new \LogicException('В базе данных нет ни одного подходящего админа категорий');
        }
        $entity->setCurator($categoryAdmin);
    }

    public function notifyRequest(RegistrationRequestEvent $event)
    {
        $registrationRequest = $event->getRegistrationRequest();
        $curator = $registrationRequest->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject(
                $curator->getUsername().' Запрос на регистрацию нового эксперта '.$registrationRequest->getEmail()
            )
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/registrationRequest.html.twig',
                    [
                        'curator'    => $curator,
                        'email'      => $registrationRequest->getEmail(),
                        'message'    => $registrationRequest->getMessage(),
                        'categories' => $registrationRequest->getCategories(),
                        'id'         => $registrationRequest->getId(),
                    ]
                )
            );
        $this->mailer->send($message);
    }

    public function createExpertApprove(
        ApproveRegistrationEvent $event,
        $eventName,
        EventDispatcherInterface $dispatcher
    ) {
        $registrationRequest = $event->getRegistrationRequest();
        $invite = new Invite();
        $invite->setIsFromFeedback(true)
            ->setCurator($registrationRequest->getCurator())
            ->setEmail($registrationRequest->getEmail());
        $this->em->persist($invite);
        $registrationRequest->setIsApproved(true);

        $dispatcher->dispatch(InviteEvents::SEND, new InviteSendEvent($invite));
    }

    public function flush()
    {
        $this->em->flush();
    }
}
