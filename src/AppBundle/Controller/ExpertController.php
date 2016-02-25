<?php
/**
 * Date: 25.02.16
 * Time: 14:35
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ExpertController extends BaseController
{
    /**
     * @Route("/profile/expert/published_items/{page}/{slug}", name="expert_published_items",
     *     defaults={"page":1, "slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     *
     */
    public function publishedItemsAction($page, Category $category = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertPublishedQuery($this->getUser(), $category);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            self::LIMIT_PER_PAGE
        );

        return $this->render('Expert/publishedItems.html.twig',
            [self::KEY_PAGINATION => $pagination, self::KEY_USER => $this->getUser()]);
    }

    /**
     * @Route("/profile/expert/not_published_items/{page}/{slug}", name="expert_not_published_items",
     *     defaults={"page":1, "slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     *
     */
    public function notPublishedItemsAction($page, Category $category = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertNotPublishedQuery($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            self::LIMIT_PER_PAGE
        );

        return $this->render('Expert/notPublishedItems.html.twig', [self::KEY_PAGINATION => $pagination,
                                                                    self::KEY_USER       => $this->getUser()]);
    }


    /**
     * @Route("/profile/expert/categories/{page}", name="expert_categories", defaults={"page":1})
     */
    public function categoriesAction()
    {
        return $this->render('Expert/categories.html.twig');
    }

    public function _menuAction()
    {
        $publishedCount = $this->getEm()->getRepository('AppBundle:Product')->getCountPublishedByExpert($this->getUser());
        $notPublishedCount = $this->getEm()->getRepository('AppBundle:Product')
            ->getCountNotPublishedByExpert($this->getUser());

        return $this->render('Expert/_menu.html.twig',
            ['publishedItemsCount'    => $publishedCount,
             'notPublishedItemsCount' => $notPublishedCount
            ]);
    }
}