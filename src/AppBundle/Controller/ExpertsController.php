<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Form\SearchTypeFabric;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpertsController extends BaseController
{
    const KEY_EXPERT_OPINIONS = 'expertOpinions';
    const KEY_EXPERTS = 'experts';
    const LIMIT_OPINIONS_PER_PAGE = 5;
    const KEY_EXPERT = 'expert';
    const KEY_CURRENT_CATEGORY = 'currentCategory';

    /**
     * @Route("/experts", name="experts_list")
     */
    public function listAction()
    {
        $experts = $this->getEm()->getRepository('AppBundle:User')->findExperts();
        return $this->render('Experts/list.html.twig', [
            self::KEY_EXPERTS => $experts
        ]);
    }

    /**
     * @Route("/experts/{username}", name="experts_detail")
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     */
    public function detailAction(User $user)
    {
        if (!$user->hasRole(User::ROLE_EXPERT)) {
            throw new NotFoundHttpException();
        }

        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertQuery($user);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            1,
            self::LIMIT_OPINIONS_PER_PAGE
        );

        return $this->render('Experts/detail.html.twig', [
            self::KEY_EXPERT          => $user,
            self::KEY_EXPERT_OPINIONS => $pagination
        ]);
    }

    /**
     * @Route("/profile/expert", name="experts_profile")
     * @Security("has_role('ROLE_EXPERT')")
     */
    public function profileAction()
    {
        return $this->render('Experts/profile.html.twig');
    }

    /**
     * @Route("/profile/expert/create/{slug}", name="experts_create")
     * @Security("has_role('ROLE_EXPERT')")
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function createAction(Request $request, Category $category)
    {
        /** @var User $expert */
        $expert = $this->getUser();
        if (!$expert->getCategories()->contains($category)) {
            throw new AccessDeniedHttpException();
        }
        $form = (new SearchTypeFabric())->create($this->get('form.factory'), $category);

        $form->handleRequest($request);
        $products = [];
        if ($form->isValid()) {
            /** @var CommonProductSearch $searchParams */
            $searchParams = $form->getData();
            $products = $this->getEm()->getRepository('AppBundle:Product')->findByCharacteristicsQuery($searchParams, $category)->getResult();
        }

        return $this->render('Experts/create.html.twig', [self::KEY_CURRENT_CATEGORY => $category, 'form' => $form->createView(), 'products' => $products]);
    }
}
