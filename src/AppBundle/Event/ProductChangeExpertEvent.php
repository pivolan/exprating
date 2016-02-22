<?php
/**
 * Date: 16.02.16
 * Time: 22:42
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
}