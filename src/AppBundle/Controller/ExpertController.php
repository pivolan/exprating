<?php

/**
 * Date: 25.02.16
 * Time: 14:35.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_EXPERT')")
 * Class ExpertController
 */
class ExpertController extends BaseController
{
    /**
     * @Route("/profile/expert/published_items/{page}/{slug}", name="expert_published_items",
     *     defaults={"page":1, "slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function publishedItemsAction(Request $request, $page, Category $category = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertPublishedQuery(
            $this->getUser(),
            $category
        );
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            self::LIMIT_PER_PAGE
        );

        $template = 'Expert/publishedItems.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Expert/part.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_PAGINATION => $pagination,
                self::KEY_USER       => $this->getUser(),
                self::KEY_PAGE       => $page,
                self::KEY_CATEGORY   => $category,
            ]
        );
    }

    /**
     * @Route("/profile/expert/not_published_items/{page}/{slug}", name="expert_not_published_items",
     *     defaults={"page":1, "slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function notPublishedItemsAction(Request $request, $page, Category $category = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertNotPublishedQuery(
            $this->getUser(),
            $category
        );
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            self::LIMIT_PER_PAGE
        );

        $template = 'Expert/notPublishedItems.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Expert/part.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_PAGINATION => $pagination,
                self::KEY_USER       => $this->getUser(),
                self::KEY_PAGE       => $page,
                self::KEY_CATEGORY   => $category,
            ]
        );
    }

    /**
     * @Route("/profile/expert/categories/{page}/{slug}", name="expert_categories",
     *     defaults={"page":1, "slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function categoriesAction(Request $request, $page, Category $category = null)
    {
        $query = [];
        if ($category) {
            $query = $this->getEm()->getRepository('AppBundle:Category')->getProductsRecursiveQuery($category);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            self::LIMIT_PER_PAGE
        );

        $template = 'Expert/categories.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Expert/part.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_PAGINATION => $pagination,
                self::KEY_USER       => $this->getUser(),
                self::KEY_PAGE       => $page,
                self::KEY_CATEGORY   => $category,
            ]
        );
    }
}
