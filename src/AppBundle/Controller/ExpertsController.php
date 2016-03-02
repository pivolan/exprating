<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExpertsController extends BaseController
{
    const KEY_EXPERT_OPINIONS = 'expertOpinions';
    const LIMIT_OPINIONS_PER_PAGE = 5;
    const KEY_EXPERT = 'expert';

    /**
     * @Route("/experts", name="experts_list")
     */
    public function listAction()
    {
        $experts = $this->getEm()->getRepository('AppBundle:User')->findExperts();

        return $this->render(
            'Experts/list.html.twig',
            [
                self::KEY_EXPERTS => $experts,
            ]
        );
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
}
