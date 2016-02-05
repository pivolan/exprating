<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    const KEY_PRODUCTS = 'products';
    const KEY_POPULAR_PRODUCTS = 'popularProducts';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $productRepository = $this->getEm()->getRepository('AppBundle:Product');
        $products = $productRepository->findNew();
        $popularProducts = $productRepository->findPopular();
        // replace this example code with whatever you need
        return $this->render('index/index.html.twig', [
            self::KEY_PRODUCTS         => $products,
            self::KEY_POPULAR_PRODUCTS => $popularProducts]);
    }
}
