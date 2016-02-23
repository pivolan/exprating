<?php
/**
 * Date: 23.02.16
 * Time: 2:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Exprating\CharacteristicBundle\Tests\Entity\ProductCharacteristicTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends BaseController
{
    const KEY_USER = 'user';

    /**
     * @Route("/admin/experts/{page}", name="admin_experts", defaults={"page": 1})
     *
     * @param Request $request
     * @param int     $page
     */
    public function expertsAction(Request $request, $page = 1)
    {
        $experts = $this->getUser()->getExperts();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $experts,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $this->render('Admin/experts.html.twig', [self::KEY_PAGINATION => $pagination]);
    }

    /**
     * @Route("/admin/expert/{username}/edit", name="admin_expert_edit")
     * @param Request $request
     * @param User    $user
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userEditAction(Request $request, User $user)
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Изменения успешно сохранены');
        }
        return $this->render('Admin/userEdit.html.twig', [self::KEY_USER => $user, self::KEY_FORM => $form]);
    }

    /**
     * @Route("/admin/expert/{username}", name="admin_expert_detail")
     * @param Request $request
     * @param User    $user
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userDetailAction(Request $request, User $user)
    {
        return $this->render('Admin/userDetail.html.twig', [self::KEY_USER => $user]);
    }
}