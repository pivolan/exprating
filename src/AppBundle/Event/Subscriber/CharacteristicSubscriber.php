<?php
/**
 * Date: 17.03.16
 * Time: 0:42
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\User;
use AppBundle\Event\Characteristic\CharacteristicCreateEvent;
use AppBundle\Event\Characteristic\CharacteristicEvents;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CharacteristicSubscriber implements EventSubscriberInterface
{
    /**
     * @var Slugify
     */
    protected $slugify;
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

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, \Twig_Environment $twig, Slugify $slugify)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->twig = $twig;
        $this->slugify = $slugify;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            CharacteristicEvents::CREATED => [['create', 2], ['createNotify', 1], ['flush'],],
        ];
    }

    public function create(CharacteristicCreateEvent $event)
    {
        $characteristic = $event->getCharacteristic();
        $characteristic->setSlug($this->slugify->slugify($characteristic->getName()));
    }

    public function createNotify(CharacteristicCreateEvent $event)
    {
        $characteristic = $event->getCharacteristic();
        $category = $event->getCategory();
        $product = $event->getProduct();
        $user = $event->getUser();
        $admins = $this->em->getRepository('AppBundle:User')->findEmails(User::ROLE_ADMIN);
        $emails = [];
        foreach ($admins as $email) {
            $emails[] = $email['email'];
        }
        $message = \Swift_Message::newInstance()
            ->setSubject('Создана новая характеристика товара '.$characteristic->getName())
            ->setTo($emails)
            ->setBody(
                $this->twig->render(
                    'Email/CharacteristicCreateNotify.html.twig',
                    [
                        'user'           => $user,
                        'characteristic' => $characteristic,
                        'category'       => $category,
                        'product'        => $product,
                    ]
                )
            );
        $this->mailer->send($message);
    }

    public function flush()
    {
        $this->em->flush();
    }
}
