<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 03.05.16
 * Time: 23:03
 */

namespace AppBundle\Dto\ImportPictures;

use AppBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints\ImportImageDuplicate;


/**
 * Class ImportImage
 * @package AppBundle\Dto\ImportPictures
 *
 * @ImportImageDuplicate
 */
class ImportImage
{
    /**
     * @var $product Product
     */
    protected $product;

    /**
     * @var \array $url
     */
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
     * @return \array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param \array $urls
     */
    public function setUrls(array $urls)
    {
        $this->urls = $urls;
    }
}
