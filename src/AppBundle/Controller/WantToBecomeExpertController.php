<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CreateExpertRequest;
use AppBundle\Entity\User;
use AppBundle\Event\User\ApproveCreateExpertEvent;
use AppBundle\Event\User\CreateExpertRequestEvent;
use AppBundle\Event\User\UserEvents;
use AppBundle\Form\CreateExpertRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WantToBecomeExpertController extends BaseController
{
    const KEY_CREATE_EXPERT_REQUEST = 'createExpertRequest';
    const FLASH_CREATE_EXPERT_REQUEST_SENDED = 'flash.create_expert_request_sended';

    /**
     * @Route("/want-to-become-an-expert", name="request_create_expert")
     */
    public function requestAction(Request $request)
    {
        $createExpertRequest = new CreateExpertRequest();

        $form = $this->createForm(CreateExpertRequestType::class, $createExpertRequest);

        $form->handleRequest($request);

        $categories = $this->getEm()->getRepository('AppBundle:Category')->getForJsTree();

        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                UserEvents::CREATE_EXPERT_REQUEST,
                new CreateExpertRequestEvent($createExpertRequest)
            );
            $this->addFlash(self::FLASH_CREATE_EXPERT_REQUEST_SENDED, 'Ваш запрос успешно отправлен');
        }

        return $this->render(
            'WantToBecomeExpert/request.html.twig',
            [self::KEY_FORM => $form->createView(),
            self::KEY_CATEGORIES => $categories]
        );
    }

    /**
     * @Route("/want-to-become-an-expert/approve/{id}", name="approve_create_expert")
     *
     * @ParamConverter(name="createExpertRequest", class="AppBundle\Entity\CreateExpertRequest", options={"mapping":{"id":"id"}})
     */
    public function approveAction(CreateExpertRequest $createExpertRequest)
    {
        $this->get('event_dispatcher')->dispatch(
            UserEvents::CREATE_EXPERT_APPROVE,
            new ApproveCreateExpertEvent($createExpertRequest, $this->getUser())
        );

        return $this->render(
            'WantToBecomeExpert/approve.html.twig',
            [self::KEY_CREATE_EXPERT_REQUEST => $createExpertRequest]
        );
    }
}
