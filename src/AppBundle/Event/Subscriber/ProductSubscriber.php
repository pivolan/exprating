<?php
/**
 * Date: 19.02.16
 * Time: 18:43
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\User;
use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductEventInterface;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductRejectEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Event\ProductReservationOverEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ProductSubscriber implements EventSubscriberInterface
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

    function __construct(\Swift_Mailer $mailer, EntityManager $em, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::RESERVATION      => [['reserveProduct', 0], ['flush']],
            ProductEvents::PUBLISH_REQUEST  => [
                ['publishRequestProduct', 1],
                ['notifyCurator'],
                ['flush']
            ],
            ProductEvents::APPROVE          => [['approveProduct', 1], ['onApproveNotifyExpert'], ['flush']],
            ProductEvents::REJECT           => [['rejectProduct', 1], ['onRejectNotifyExpert'], ['flush']],
            ProductEvents::PUBLISH          => [['publishProduct', 1], ['onPublishNotifyExpert'], ['flush']],
            ProductEvents::CHANGE_EXPERT    => [['changeExpert', 1], ['flush']],
            ProductEvents::RESERVATION_OVER => [['reserveOver', 0], ['onReserveOverNotifyExpert', 1], ['flush']],
            ProductEvents::COMMENTED        => [['commentProduct', 1], ['flush']],
        ];
    }

    public function reserveProduct(ProductReservationEvent $event)
    {
        $expert = $event->getExpert();
        $product = $event->getProduct();
        $product->setExpertUser($expert)
            ->setReservedAt(new \DateTime());
    }

    public function publishRequestProduct(ProductPublishRequestEvent $event,
                                          $eventName,
                                          EventDispatcherInterface $dispatcher)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();

        if ($expert) {
            $curatorDecision = new CuratorDecision();
            $curatorDecision->setCurator($expert->getCurator())
                ->setProduct($product)
                ->setUpdatedAt(new \DateTime());
            $this->em->persist($curatorDecision);
        } elseif ($expert->hasRole(User::ROLE_EXPERT_CURATOR)) {
            $dispatcher->dispatch(ProductEvents::PUBLISH, $event);
            $event->stopPropagation();
        }
    }

    public function notifyCurator(ProductPublishRequestEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Новая публикация от Эксперта ' . $expert->getFullName() . ' ' . $product->getName())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyCuratorPublishRequestProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function approveProduct(ProductApproveEvent $event, $eventName,
                                   EventDispatcherInterface $dispatcher)
    {
        $product = $event->getProduct();

        foreach ($product->getCuratorDecisions() as $curatorDecision) {
            if ($curatorDecision->getStatus() == CuratorDecision::STATUS_WAIT) {
                $curatorDecision->setStatus(CuratorDecision::STATUS_APPROVE)
                    ->setUpdatedAt(new \DateTime());
            }
        }
        $dispatcher->dispatch(ProductEvents::PUBLISH, $event);
    }

    public function onApproveNotifyExpert(ProductApproveEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была одобрена Куратором ' . $curator->getFullName() . ' - ' . $product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertApproveProduct.html.twig',
                    ['product' => $product, 'curator' => $curator]
                )
            );
        $this->mailer->send($message);
    }

    public function rejectProduct(ProductRejectEvent $event)
    {
        $product = $event->getProduct();

        foreach ($product->getCuratorDecisions() as $curatorDecision) {
            if ($curatorDecision->getStatus() == CuratorDecision::STATUS_WAIT) {
                $curatorDecision->setStatus(CuratorDecision::STATUS_REJECT)
                    ->setRejectReason($event->getReason())
                    ->setUpdatedAt(new \DateTime());
            }
        }
    }

    public function onRejectNotifyExpert(ProductRejectEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была отвергнута Куратором ' . $curator->getFullName() . ' - ' . $product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertRejectProduct.html.twig',
                    ['product' => $product, 'curator' => $curator, 'rejectReason' => $event->getReason()]
                )
            );
        $this->mailer->send($message);
    }

    public function publishProduct(ProductEventInterface $event)
    {
        $product = $event->getProduct();

        $product->setReservedAt(null)
            ->setIsEnabled(true)->setEnabledAt(new \DateTime());
    }

    public function onPublishNotifyExpert(ProductEventInterface $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была опубликована ' . $product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertPublishedProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function changeExpert($event)
    {
        //todo
    }

    public function reserveOver(ProductReservationOverEvent $event)
    {
        $product = $event->getProduct();
        $product->setExpertUser(null)
            ->setReservedAt(null);
    }

    public function onReserveOverNotifyExpert(ProductReservationOverEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $message = \Swift_Message::newInstance()
            ->setSubject('Время резервирования товара ' . $product->getName() . ' закончено')
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertReservationOverProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function commentProduct($event)
    {
        //todo
    }

    public function flush($event)
    {
        $this->em->flush();
    }
}