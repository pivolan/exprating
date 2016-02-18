<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductEventInterface;
use AppBundle\Event\ProductRejectEvent;
use AppBundle\Event\ProductReservationOverEvent;
use Doctrine\ORM\EntityManager;

class NotifyExpert
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


    public function onReject(ProductRejectEvent $event)
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

    public function onApprove(ProductApproveEvent $event)
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

    public function onPublished(ProductEventInterface $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Ваша публикация была опубликована ' . $product->getName())
            ->setTo($expert->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyExpertApproveProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }

    public function onReserveOver(ProductReservationOverEvent $event)
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
} 