<?php

/**
 * Date: 25.02.16
 * Time: 14:35.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_EXPERT')")
 * Class ExpertController
 */
class ExpertController extends BaseController
{
    const KEY_INVITE = 'invite';

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

    /**
     * @param User $expert
     *
     * @Security("is_granted('DETAIL_VIEW', expert)")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailViewAction(User $expert)
    {
        $invite = $this->getEm()->getRepository('AppBundle:Invite')->findOneByExpert($expert);

        if ($invite) {
            return $this->render('Expert/detailView.html.twig', [self::KEY_INVITE => $invite]);
        } else {
            return new Response();
        }
    }

    /**
     * @Route("/profile/expert/my_pages", name="expert_my_pages")
     * @return Response
     */
    public function myPagesAction()
    {
        return $this->render("Curator/myExperts.html.twig");
    }
}
