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