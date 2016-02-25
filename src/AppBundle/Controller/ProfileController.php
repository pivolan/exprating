<?php
/**
 * Date: 25.02.16
 * Time: 14:18
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile/expert", name="expert_profile")
     */
    public function expertAction()
    {
        return $this->render('Profile/expert.html.twig', [self::KEY_USER =>$this->getUser()]);
    }

    /**
     * @Route("/profile/curator", name="curator_profile")
     */
    public function curatorAction()
    {
        return $this->render('Profile/curator.html.twig', [self::KEY_USER =>$this->getUser()]);

    }

    /**
     * @Route("/profile/category_admin", name="category_admin_profile")
     */
    public function adminCategoryAction()
    {
        return $this->render('Profile/category_admin.html.twig', [self::KEY_USER =>$this->getUser()]);

    }

    /**
     * @Route("/profile/moderator", name="moderator_profile")
     */
    public function moderatorAction()
    {
        return $this->render('Profile/moderator.html.twig', [self::KEY_USER =>$this->getUser()]);

    }

    /**
     * @Route("/profile/admin", name="admin_profile")
     */
    public function adminAction()
    {
        return $this->render('Profile/admin.html.twig', [self::KEY_USER =>$this->getUser()]);
    }
}