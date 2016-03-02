<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductPublishRequestEvent extends Event implements ProductEventInterface
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductPublishEvent constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product = null)
    {
        $this->product = $product;
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
}
