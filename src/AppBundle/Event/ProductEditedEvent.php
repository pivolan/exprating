<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ProductEditedEvent extends Event implements ProductEventInterface
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var User
     */
    protected $user;

    /**
     * ProductVisitEvent constructor.
     *
     * @param Product $product
     * @param User    $user
     */
    public function __construct(Product $product, User $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return Request
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Request $user
     */
    public function setUser(Request $user)
    {
        $this->user = $user;
    }
}
