<?php
/**
 * Date: 17.03.16
 * Time: 0:42
 */

namespace AppBundle\Event\Subscriber;

use AppBundle\Entity\User;
use AppBundle\Event\Characteristic\CharacteristicCreateEvent;
use AppBundle\Event\Characteristic\CharacteristicEvents;
use AppBundle\Event\Comment\CommentEvents;
use AppBundle\Event\Comment\CommentPublishEvent;
use AppBundle\Event\Comment\CommentRejectEvent;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentSubscriber implements EventSubscriberInterface
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

    public function commentPublish(CommentPublishEvent $event)
    {
        $comment = $event->getComment();
        $comment->setIsPublished(true)
            ->setPublishedAt(new \DateTime());
    }


    public function commentReject(CommentRejectEvent $event)
    {
        $this->em->remove($event->getComment());
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            CommentEvents::COMMENT_PUBLISH => [['commentPublish', 2], ['flush'],],
            CommentEvents::COMMENT_REJECT  => [['commentReject', 2], ['flush'],],
        ];
    }


    public function flush()
    {
        $this->em->flush();
    }
}
