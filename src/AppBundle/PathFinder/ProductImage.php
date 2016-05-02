<?php
/**
 * Date: 04.03.16
 * Time: 10:48
 */

namespace AppBundle\PathFinder;

use AppBundle\Entity\Product;

class ProductImage
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var Product
     */
    protected $productId;

    /**
     * ProductImage constructor.
     *
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @inheritdoc
     */
    public function findFolder()
    {
        $this->_mkDir();
        return $this->rootDir.'/../web'.$this->relativeFolder();
    }

    public function relativeFolder()
    {
        if (!$this->getProductId()) {
            throw new PathFinderException('Set Product id before use findFolder function');
        }

        return '/uploads/product/'.$this->getProductId() % 10000 .'/'.
               $this->getProductId().'/';
    }

    public function _mkDir(){
        if(!$this->getProductId()){
            return;
        }
        $f1 = $this->rootDir.'/../web'. '/uploads/product/'.$this->getProductId() % 10000 ;
        if(!is_dir($f1)){
            mkdir($f1);
        }
        $f2 = $this->rootDir.'/../web'. '/uploads/product/'.$this->getProductId() % 10000 .'/'. $this->getProductId().'/';

        if(!is_dir($f2)){
            mkdir($f2);
        }
    }
    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getWebDir()
    {
        return $this->rootDir.'/../web';
    }
}
