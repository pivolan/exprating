<?php

/**
 * Date: 16.02.16
 * Time: 22:42.
 */

namespace AppBundle\Event;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Exprating\ImportXmlBundle\Dto\SearchInput;
use Exprating\SearchBundle\Dto\SearchParams;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ProductSaveQueryStringEvent extends Event
{
    /**
     * @var SearchParams
     */
    protected $searchParams;

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
     * @param SearchParams $searchParams
     * @param User         $user
     */
    public function __construct(SearchParams $searchParams, Product $product, User $user)
    {
        $this->searchParams = $searchParams;
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

    /**
     * @return SearchParams
     */
    public function getSearchParams()
    {
        return $this->searchParams;
    }

    /**
     * @param SearchParams $searchParams
     */
    public function setSearchParams($searchParams)
    {
        $this->searchParams = $searchParams;
    }
}
