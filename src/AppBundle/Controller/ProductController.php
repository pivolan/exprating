<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    const KEY_PRODUCT = 'product';

    /**
     * @Route("/product/{slug}", name="product_show")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function showAction(Request $request, Product $product)
    {
        return $this->render('product/show.html.twig', [
            self::KEY_PRODUCT => $product
        ]);
    }
}
