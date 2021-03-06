<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class ProductApproveEvent extends Event implements ProductEventInterface
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
     * ProductApproveEvent constructor.
     *
     * @param Product $product
     * @param User    $curator
     */
    public function __construct(Product $product = null, User $curator = null)
    {
        $this->product = $product;
        $this->curator = $curator;
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
     * @return User
     */
    public function getCurator()
    {
        return $this->curator;
    }

    /**
     * @param User $curator
     */
    public function setCurator($curator)
    {
        $this->curator = $curator;
    }
}
