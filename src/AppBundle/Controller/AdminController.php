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
use Symfony\Component\HttpFoundation\JsonResponse;
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
    const RESPONSE_OK = 'ok';

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
     * @Route("/admin/import_settings", name="admin_import_settings")
     */
    public function importSettingsAction()
    {
        /** @var Category[] $categories */
        $categoryRepository = $this->getEm()->getRepository('AppBundle:Category');
        $categories = $categoryRepository->getAll();
        $categoriesImport = $this->get('doctrine.orm.import_entity_manager')
            ->getRepository('ExpratingImportBundle:Categories')
            ->findAll();
        $categoryAssociate = [];
        foreach ($categories as $category) {
            $categoryAssociate[$category->getSlug()] = $categoryRepository->getPathString($category);
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
     * @Route("/admin/all_products/{page}", name="admin_all_products", defaults={"page":1})
     * @return Response
     */
    public function allProductAction($page)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->getAllQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            24
        );

        return $this->render(
            'Admin/allProducts.html.twig',
            [
                self::KEY_PAGINATION => $pagination,
                self::KEY_PAGE       => $page,
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
        $irecommendCategory = $emImport->getRepository('ExpratingImportBundle:Categories')->find($aliasCategoryId);
        $category = $this->getEm()->getRepository('AppBundle:Category')->find($categorySlug);
        if ($aliasCategory && $category) {
            $aliasCategory->setCategoryExpratingId($categorySlug);
            $emImport->flush();

            return new Response(self::RESPONSE_OK);
        } elseif ($category && $irecommendCategory) {
            $aliasCategory = new AliasCategory();
            $aliasCategory->setCategoryIrecommend($irecommendCategory)
                ->setCategoryExpratingId($category);
            $emImport->persist($aliasCategory);
            $emImport->flush();

            return new Response(self::RESPONSE_OK);
        }

        return new Response('error', 400);
    }

    /**
     * @Route("/admin/category/move", name="admin_category_move")
     */
    public function moveCategoryAction(Request $request)
    {
        $categorySlug = $request->get('category');
        $parentSlug = $request->get('parent');
        $position = $request->get('position');
        $categoryRepository = $this->getEm()->getRepository('AppBundle:Category');
        $category = $categoryRepository->find($categorySlug);
        if (!$category) {
            throw $this->createNotFoundException();
        }
        $parent = $parentSlug ? $categoryRepository->find($parentSlug) : null;
        if (!$parent) {
            $parent = $categoryRepository->find(Category::ROOT_SLUG);
        }
        $category->setParent($parent);
        if ($position > 0) {
            $categoryRepository->moveUp($category, true);
            $categoryRepository->moveDown($category, $position);
        }

        $this->getEm()->flush();

        return new JsonResponse(
            [
                'slug'     => $category->getSlug(),
                'path'     => $categoryRepository->getPathString($category),
                'edit_url' => $this->generateUrl('category_admin_categories', ['slug' => $category->getSlug()]),
                'show_url' => $this->generateUrl(
                    'product_list',
                    ['slug' => $category->getSlug()]
                ),
            ]
        );
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     */
    public function createCategoryAction(Request $request)
    {
        $name = $request->get('name');
        $parentSlug = $request->get('parent');
        $categoryRepository = $this->getEm()->getRepository('AppBundle:Category');
        $parent = $categoryRepository->find($parentSlug);
        $parent = $parent ?: $categoryRepository->find(Category::ROOT_SLUG);
        $category = (new Category())
            ->setName($name)
            ->setParent($parent)
            ->setSlug($this->get('appbundle.slugify')->slugify($name).time());
        $categoryRepository->persistAsFirstChildOf($category, $parent);

        $this->getEm()->flush();

        return new JsonResponse(
            [
                'slug'     => $category->getSlug(),
                'path'     => $categoryRepository->getPathString($category),
                'edit_url' => $this->generateUrl('category_admin_categories', ['slug' => $category->getSlug()]),
                'show_url' => $this->generateUrl(
                    'product_list',
                    ['slug' => $category->getSlug()]
                ),
            ]
        );
    }

    /**
     * @Route("/admin/category/rename", name="admin_category_rename")
     */
    public function renameCategoryAction(Request $request)
    {
        $name = $request->get('name');
        $categorySlug = $request->get('slug');
        $categoryRepository = $this->getEm()->getRepository('AppBundle:Category');
        $category = $categoryRepository->find($categorySlug);
        if (!$category) {
            throw $this->createNotFoundException();
        }
        $category->setName($name);
        $this->getEm()->flush();

        return new JsonResponse(
            [
                'slug'     => $category->getSlug(),
                'path'     => $categoryRepository->getPathString($category),
                'edit_url' => $this->generateUrl('category_admin_categories', ['slug' => $category->getSlug()]),
                'show_url' => $this->generateUrl(
                    'product_list',
                    ['slug' => $category->getSlug()]
                ),
            ]
        );
    }
}
