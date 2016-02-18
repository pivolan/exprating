<?php
/**
 * Date: 16.02.16
 * Time: 22:42
 */

namespace AppBundle\Event;


use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class ProductReservationEvent extends Event implements ProductEventInterface
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
     * ProductReservationEvent constructor.
     *
     * @param Product $product
     * @param User    $expert
     */
    public function __construct(Product $product = null, User $expert = null)
    {
        $this->product = $product;
        $this->expert = $expert;
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