<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductChangeExpertEvent extends Event
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var User
     */
    protected $newExpert;

    /**
     * @var User
     */
    protected $previousExpert;

    /**
     * @var User
     */
    protected $curator;
}