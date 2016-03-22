<?php
/**
 * Date: 17.03.16
 * Time: 0:42
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\User;
use AppBundle\Event\Category\CategoryEvents;
use AppBundle\Event\Category\CategoryCreateEvent;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CategorySubscriber implements EventSubscriberInterface
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
            CategoryEvents::CATEGORY_CREATE => [['create', 2], ['flush'],],
        ];
    }

    public function create(CategoryCreateEvent $event)
    {
        $category = $event->getCategory();
        $this->em->persist($category);
        $this->em->persist($category->getRatingSettings());
        $this->em->persist($category->getSeo());
    }

    public function flush()
    {
        $this->em->flush();
    }
}
