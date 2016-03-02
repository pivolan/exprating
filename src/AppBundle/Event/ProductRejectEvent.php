<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductRejectEvent extends Event implements ProductEventInterface
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

    /**
     * ProductRejectEvent constructor.
     *
     * @param Product $product
     * @param User    $curator
     * @param string  $reason
     */
    public function __construct(Product $product, User $curator, $reason)
    {
        $this->product = $product;
        $this->curator = $curator;
        $this->reason = $reason;
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

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }
}
