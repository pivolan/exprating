<?php

namespace Exprating\ExpertBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PermissionsEditController
 * @package Exprating\ExpertBundle\Controller
 *
 * @Security("has_role('ROLE_ADMIN')")
 *
 */
class PermissionsEditController extends Controller
{
    /**
     * @Route("/cabinet/admin/user-list", name="expert_admin_user_list")
     */
    public function userListAction()
    {
        return $this->render('ExpratingExpertBundle:PermissionsEdit:user_list.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/cabinet/admin/user-edit/{username}", name="expert_admin_user_edit")
     */
    public function userEditAction($username)
    {
        return $this->render('ExpratingExpertBundle:PermissionsEdit:user_edit.html.twig', [
            // ...
        ]);
    }

}
