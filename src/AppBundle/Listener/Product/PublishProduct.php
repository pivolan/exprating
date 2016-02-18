<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Event\ProductPublishRequestEvent;
use Doctrine\ORM\EntityManager;

class PublishProduct
{
    public function handler(ProductPublishRequestEvent $event)
    {
        $product = $event->getProduct();

        $product->setReservedAt(null)
            ->setIsEnabled(true)->setEnabledAt(new \DateTime());
    }
} 