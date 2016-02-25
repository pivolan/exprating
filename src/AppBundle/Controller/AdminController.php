<?php
/**
 * Date: 23.02.16
 * Time: 2:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends BaseController
{
    const KEY_USER = 'user';

    /**
     * @Route("/admin/experts/{page}/{username}", name="admin_experts", defaults={"page": 1, "username": null})
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     * @param Request $request
     * @param int     $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expertsAction(Request $request, $page = 1, User $user = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:User')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $treeHtml = $this->getEm()->getRepository('AppBundle:Category')->childrenHierarchy(null, false,
            ['decorate' => true, 'representationField' => 'slug', 'html' => true]);
        if (!$user) {
            $user = $this->getUser();
        }
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Изменения успешно сохранены');
        }


        return $this->render('Admin/experts.html.twig', [self::KEY_PAGINATION => $pagination,
                                                         self::KEY_TREE_HTML  => $treeHtml,
                                                         self::KEY_FORM       => $form->createView(),
                                                         self::KEY_PAGE       =>$page
        ]);
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