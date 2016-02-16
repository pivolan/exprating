<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Event\ProductApproveEvent;
use Doctrine\ORM\EntityManager;

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

    public function handler(ProductApproveEvent $event)
    {
        $product = $event->getProduct();

        foreach ($product->getCuratorDecisions() as $curatorDecision) {
            if ($curatorDecision->getStatus() == CuratorDecision::STATUS_WAIT) {
                $curatorDecision->setStatus(CuratorDecision::STATUS_APPROVE)
                    ->setUpdatedAt(new \DateTime());
            }
        }
        $product->setReservedAt(null)
            ->setIsEnabled(true)->setEnabledAt(new \DateTime());
        $this->em->flush();
    }
}