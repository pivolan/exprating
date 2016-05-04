<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 03.05.16
 * Time: 23:03
 */

namespace AppBundle\DTO\ImportPictures;

use AppBundle\Entity\Product;
use AppBundle\PathFinder\ProductImage;

class ImportImage {
    /** @var $product Product */
    protected $product;
    /** @var Array $url */
    protected $urls;

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
     * @return Array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param Array $urls
     */
    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

}