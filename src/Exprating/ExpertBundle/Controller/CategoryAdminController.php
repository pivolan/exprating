<?php

namespace Exprating\ExpertBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CategoryAdminController.
 *
 * @Security("has_role('ROLE_EXPERT_CATEGORY_ADMIN')")
 */
class CategoryAdminController extends Controller
{
    /**
     * @Route("/cabinet/category-admin/categories", name="expert_category_admin_list")
     */
    public function categoryListAction()
    {
        return $this->render('ExpratingExpertBundle:CategoryAdmin:category_list.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/cabinet/category-admin/edit/{slug}", name="expert_category_admin_edit")
     */
    public function categoryEditAction($slug)
    {
        return $this->render('ExpratingExpertBundle:CategoryAdmin:category_edit.html.twig', [
            // ...
        ]);
    }
}
