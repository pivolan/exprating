<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\Product;
use AppBundle\Event\ProductPublishEvent;
use AppBundle\Event\ProductReservationEvent;
use Doctrine\ORM\EntityManager;

class PublishProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function handler(ProductPublishEvent $event)
    {
        $product = $event->getProduct();
        $expert = $product->getExpertUser();

        $product->setCuratorDecisionStatus(Product::CURATOR_DECISION_STATUS_WAIT)
            ->setReservationAt(new \DateTime());
        $this->em->persist($product);
        $this->em->flush();
    }
} 