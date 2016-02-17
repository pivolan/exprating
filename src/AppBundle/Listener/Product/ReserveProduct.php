<?php
/**
 * Date: 16.02.16
 * Time: 23:40
 */

namespace AppBundle\Listener\Product;


use AppBundle\Event\ProductReservationEvent;
use Doctrine\ORM\EntityManager;

class ReserveProduct
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function handler(ProductReservationEvent $event)
    {
        $expert = $event->getExpert();
        $product = $event->getProduct();
        $product->setExpertUser($expert)
            ->setReservedAt(new \DateTime());
        $this->em->flush();
    }
} 