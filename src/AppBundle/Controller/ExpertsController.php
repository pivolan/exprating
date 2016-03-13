<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpertsController extends BaseController
{
    const KEY_EXPERT_OPINIONS = 'expertOpinions';
    const LIMIT_OPINIONS_PER_PAGE = 5;
    const KEY_EXPERT = 'expert';
    const FLASH_PROFILE_SAVED = 'flash.profile_saved';

    /**
     * @Route("/experts/{page}", name="experts_list", defaults={"page":1})
     */
    public function listAction($page)
    {
        $query = $this->getEm()->getRepository('AppBundle:User')->findExpertsQuery();
        $paginator = $this->get('knp_paginator');
        $experts = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_OPINIONS_PER_PAGE
        );

        return $this->render(
            'Experts/list.html.twig',
            [
                self::KEY_EXPERTS => $experts,
            ]
        );
    }

    /**
     * @Route("/expert/{username}", name="experts_detail")
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     */
    public function detailAction(User $user)
    {
        if (!$user->hasRole(User::ROLE_EXPERT)) {
            throw new NotFoundHttpException();
        }

        $query = $this->getEm()->getRepository('AppBundle:Product')->findByExpertPublishedQuery($user);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            1,
            self::LIMIT_OPINIONS_PER_PAGE
        );

        return $this->render(
            'Experts/detail.html.twig',
            [
                self::KEY_EXPERT          => $user,
                self::KEY_EXPERT_OPINIONS => $pagination,
            ]
        );
    }

    /**
     * @Route("/expert/edit/{username}", name="experts_detail_edit")
     *
     * @ParamConverter(name="user", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     * @Security("is_granted('EDIT', expert)")
     *
     * @param Request $request
     * @param User    $expert
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, User $expert)
    {
        $form = $this->createForm(UserProfileType::class, $expert);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_PROFILE_SAVED, 'Изменения успешно сохранены');
            return $this->redirectToRoute('experts_detail_edit', ['username' => $expert->getUsername()]);
        }

        return $this->render(
            'Experts/edit.html.twig',
            [self::KEY_EXPERT => $expert, self::KEY_FORM => $form->createView()]
        );
    }
}
