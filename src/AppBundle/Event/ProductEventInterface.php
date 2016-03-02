<?php

/**
 * Date: 18.02.16
 * Time: 4:45.
 */

namespace AppBundle\Event;

use AppBundle\Entity\Product;

interface ProductEventInterface
{
    /**
     * @return Product
     */
    public function getProduct();
}
