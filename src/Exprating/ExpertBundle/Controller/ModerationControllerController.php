<?php

namespace Exprating\ExpertBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ModerationControllerController
 * @package Exprating\ExpertBundle\Controller
 * @Security("has_role('ROLE_EXPERT_CURATOR')")
 */
class ModerationControllerController extends Controller
{
    /**
     * @Route("/cabinet/moderate/wait-list", name="expert_curator_product_list_wait")
     */
    public function waitListAction()
    {
        return $this->render('ExpratingExpertBundle:ModerationController:wait_list.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/cabinet/moderate/product/{slug}", name="expert_curator_product_moderate")
     */
    public function productDetailAction($slug)
    {
        return $this->render('ExpratingExpertBundle:ModerationController:product_detail.html.twig', [
            // ...
        ]);
    }

}
