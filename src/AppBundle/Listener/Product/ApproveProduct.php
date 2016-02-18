<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Event\ProductApproveEvent;
use AppBundle\Event\ProductEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ApproveProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductApproveEvent $event, $eventName,
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
        $this->em->flush();
    }
}