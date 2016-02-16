<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductRejectEvent extends Event
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var User
     */
    protected $curator;

    /**
     * @var string
     */
    protected $reason;
} 