<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Event\ProductReservationOverEvent;
use Doctrine\ORM\EntityManager;

class ReserveOverProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductReservationOverEvent $event)
    {
        $product = $event->getProduct();
        $product->setExpertUser(null)
            ->setReservedAt(null);
    }
} 