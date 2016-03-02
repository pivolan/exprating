<?php

/**
 * Date: 25.02.16
 * Time: 14:18.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile/{username}", name="profile")
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     */
    public function expertAction(User $user)
    {
        return $this->render('Profile/user.html.twig', [self::KEY_USER => $user]);
    }
}
