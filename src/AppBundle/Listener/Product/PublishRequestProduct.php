<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\User;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PublishRequestProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductPublishRequestEvent $event, $eventName,
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
        } elseif($expert->hasRole(User::ROLE_EXPERT_CURATOR)) {
            $dispatcher->dispatch(ProductEvents::PUBLISH, $event);
            $event->stopPropagation();
        }
        $this->em->flush();
    }
} 