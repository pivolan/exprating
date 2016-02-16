<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use use AppBundle\Entity\Product;

class ProductReservationEvent extends Event
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var User
     */
    protected $expert;

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
     * @return mixed
     */
    public function getExpert()
    {
        return $this->expert;
    }

    /**
     * @param mixed $expert
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;
    }
} 