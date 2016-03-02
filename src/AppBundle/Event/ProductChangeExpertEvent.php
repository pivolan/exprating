<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Product;

class ProductChangeExpertEvent extends Event implements ProductEventInterface
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

    /**
     * ProductChangeExpertEvent constructor.
     *
     * @param Product $product
     * @param User    $newExpert
     * @param User    $previousExpert
     * @param User    $curator
     */
    public function __construct(Product $product, User $newExpert, User $previousExpert, User $curator)
    {
        $this->product = $product;
        $this->newExpert = $newExpert;
        $this->previousExpert = $previousExpert;
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
    public function getNewExpert()
    {
        return $this->newExpert;
    }

    /**
     * @param User $newExpert
     */
    public function setNewExpert($newExpert)
    {
        $this->newExpert = $newExpert;
    }

    /**
     * @return User
     */
    public function getPreviousExpert()
    {
        return $this->previousExpert;
    }

    /**
     * @param User $previousExpert
     */
    public function setPreviousExpert($previousExpert)
    {
        $this->previousExpert = $previousExpert;
    }

    /**
     * @param User $curator
     */
    public function setCurator($curator)
    {
        $this->curator = $curator;
    }

    /**
     * @return User
     */
    public function getCurator()
    {
        return $this->curator;
    }
}
