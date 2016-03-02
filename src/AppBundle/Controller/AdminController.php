<?php

/**
 * Date: 23.02.16
 * Time: 2:09.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Exprating\ImportBundle\Entity\AliasCategory;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends BaseController
{
    const KEY_USER = 'user';
    const KEY_CATEGORIES_IMPORT = 'categoriesImport';
    const KEY_CATEGORY_ASSOCIATE = 'categoryAssociate';

    /**
     * @Route("/admin/experts/{page}/{username}", name="admin_experts", defaults={"page": 1, "username": null})
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     *
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
        if (!$user) {
            $user = $this->getUser();
        }
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_MESSAGE, 'Изменения успешно сохранены');
        }

        return $this->render(
            'Admin/experts.html.twig',
            [
                self::KEY_PAGINATION => $pagination,
                self::KEY_FORM       => $form->createView(),
                self::KEY_PAGE       => $page,
                self::KEY_USER       => $user,
            ]
        );
    }

    /**
     * @Route("/admin/expert/{username}/edit", name="admin_expert_edit")
     *
     * @param Request $request
     * @param User    $user
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     *
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
     *
     * @param Request $request
     * @param User    $user
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userDetailAction(User $user)
    {
        return $this->render('Admin/userDetail.html.twig', [self::KEY_USER => $user]);
    }

    /**
     * @Route("/admin/import_settings", name="admin_import_settings")
     */
    public function importSettingsAction()
    {
        /** @var Category[] $categories */
        $categories = $this->getEm()->getRepository('AppBundle:Category')->findAll();
        $categoriesImport = $this->get('doctrine.orm.import_entity_manager')
            ->getRepository('ExpratingImportBundle:Categories')
            ->findAll();
        $categoryAssociate = [];
        foreach ($categories as $category) {
            $categoryAssociate[$category->getSlug()] = $category;
        }

        return $this->render(
            'Admin/importSettings.html.twig',
            [
                self::KEY_CATEGORIES         => $categories,
                self::KEY_CATEGORIES_IMPORT  => $categoriesImport,
                self::KEY_CATEGORY_ASSOCIATE => $categoryAssociate,
            ]
        );
    }

    /**
     * @Route("/admin/import_settings_change", name="admin_import_settings_change")
     */
    public function importSettingsChangeAction(Request $request)
    {
        $categorySlug = htmlspecialchars_decode($request->get('category_slug'));
        $aliasCategoryId = $request->get('alias_category_id');
        $emImport = $this->get('doctrine.orm.import_entity_manager');
        /** @var AliasCategory $aliasCategory */
        $aliasCategory = $emImport->getRepository('ExpratingImportBundle:AliasCategory')->find($aliasCategoryId);
        $category = $this->getEm()->getRepository('AppBundle:Category')->find($categorySlug);
        if ($aliasCategory && $category) {
            $aliasCategory->setCategoryExpratingId($categorySlug);
            $emImport->flush();

            return new Response('ok');
        }

        return new Response('error', 400);
    }
}
