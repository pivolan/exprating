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
    protected $url;
    /** @var ProductImage $pathService */
    protected $pathService;

    /**
     * @return ProductImage
     */
    public function getPathService()
    {
        return $this->pathService;
    }

    /**
     * @param ProductImage $pathService
     */
    public function setPathService($pathService)
    {
        $this->pathService = $pathService;
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
     * @return Array
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param Array $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}