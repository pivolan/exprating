<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Exprating\ImportXmlBundle\Dto\SearchInput;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ProductSaveQueryStringEvent extends Event
{
    /**
     * @var SearchInput
     */
    protected $searchInput;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var User
     */
    protected $user;

    /**
     * ProductSaveQueryStringEvent constructor.
     *
     * @param SearchInput $searchInput
     * @param User        $user
     */
    public function __construct(SearchInput $searchInput, Product $product, User $user)
    {
        $this->searchInput = $searchInput;
        $this->user = $user;
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
