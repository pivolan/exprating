<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    const KEY_PRODUCTS = 'products';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $products = $this->getEm()->getRepository('AppBundle:Product')->findAll();
        // replace this example code with whatever you need
        return $this->render('index/index.html.twig', [self::KEY_PRODUCTS => $products]);
    }
}
