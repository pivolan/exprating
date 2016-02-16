<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductReservationOverEvent extends Event
{
    /**
     * @var Product
     */
    protected $product;
}