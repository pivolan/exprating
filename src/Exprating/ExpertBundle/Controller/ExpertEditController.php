<?php

namespace Exprating\ExpertBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ExpertEditController.
 *
 * @Security("has_role('ROLE_CURATOR')")
 */
class ExpertEditController extends Controller
{
    /**
     * @Route("/cabinet/change-expert/product-list", name="expert_curator_product_list")
     */
    public function productListAction()
    {
        return $this->render('ExpratingExpertBundle:ExpertEdit:product_list.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/cabinet/change-expert/product/{slug}", name="expert_curator_product")
     */
    public function productDetailAction($slug)
    {
        return $this->render('ExpratingExpertBundle:ExpertEdit:product_detail.html.twig', [
            // ...
        ]);
    }
}
