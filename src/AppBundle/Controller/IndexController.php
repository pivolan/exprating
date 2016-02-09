<?php

namespace AppBundle\Controller;

use Exprating\SearchBundle\Form\SearchParamsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    const KEY_POPULAR_PRODUCTS = 'popularProducts';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SearchParamsType::class, null, ['action' => $this->generateUrl('product_search')]);

        $productRepository = $this->getEm()->getRepository('AppBundle:Product');
        $products = $productRepository->findNew();
        $popularProducts = $productRepository->findPopular();
        // replace this example code with whatever you need
        return $this->render('Index/index.html.twig', [
            self::KEY_PRODUCTS         => $products,
            self::KEY_POPULAR_PRODUCTS => $popularProducts,
            self::KEY_FORM_SEARCH      => $form->createView()]);
    }
}
