<?php

namespace Exprating\ExpertBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class IndexController extends BaseController
{
    /**
     * @Route("/cabinet", name="expert_cabinet")
     */
    public function publishedProductsAction()
    {
        $products = $this->getEm()->getRepository('AppBundle:Product')->findByExpertQuery($this->getUser());
        return $this->render('ExpertCabinet/publishedProducts.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/cabinet/unpublished-products", name="expert_unpublished_products")
     */
    public function unpublishedProductsAction()
    {
        $products = $this->getEm()->getRepository('AppBundle:Product')->findByExpertQuery($this->getUser());
        return $this->render('ExpertCabinet/unpublishedProducts.html.twig', ['products' => $products]);
    }
}
