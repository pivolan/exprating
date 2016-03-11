<?php
/**
 * Date: 10.03.16
 * Time: 4:13
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\User;
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
        $entity = $event->getCreateExpertRequest();
        $this->em->persist($entity);
        /** @var User $categoryAdmin */
        $categoryAdmin = $this->em->getRepository('AppBundle:User')->getRandomByCategories($entity->getCategories());
        if (!$categoryAdmin) {
            throw new \LogicException('В базе данных нет ни одного подходящего админа категорий');
        }
        $entity->setCurator($categoryAdmin);
    }

    public function notifyRequest(CreateExpertRequestEvent $event)
    {
        $createExpertRequest = $event->getCreateExpertRequest();
        $curator = $createExpertRequest->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Запрос на регистрацию нового эксперта '.$createExpertRequest->getEmail())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/createExpertRequest.html.twig',
                    [
                        'curator'    => $curator,
                        'email'      => $createExpertRequest->getEmail(),
                        'message'    => $createExpertRequest->getMessage(),
                        'categories' => $createExpertRequest->getCategories(),
                        'id'         => $createExpertRequest->getId(),
                    ]
                )
            );
        $this->mailer->send($message);
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