<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Event\ProductPublishEvent;
use Doctrine\ORM\EntityManager;

class NotifyCurator
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


    public function handler(ProductPublishEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();
        $curator = $expert->getCurator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Новая публикация от Эксперта ' . $expert->getFullName() . ' ' . $product->getName())
            ->setTo($curator->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/notifyCuratorPublishProduct.html.twig',
                    ['product' => $product]
                )
            );
        $this->mailer->send($message);
    }
} 