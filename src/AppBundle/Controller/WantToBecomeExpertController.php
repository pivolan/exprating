<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CreateExpertRequest;
use AppBundle\Entity\User;
use AppBundle\Event\User\ApproveCreateExpertEvent;
use AppBundle\Event\User\CreateExpertRequestEvent;
use AppBundle\Event\User\UserEvents;
use AppBundle\Form\CreateExpertRequestApproveType;
use AppBundle\Form\CreateExpertRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WantToBecomeExpertController extends BaseController
{
    const KEY_CREATE_EXPERT_REQUEST = 'createExpertRequest';
    const FLASH_CREATE_EXPERT_REQUEST_SENDED = 'flash.create_expert_request_sended';
    const FLASH_REGISTRATION_REQUEST_APPROVED = 'flash.registration_request_approved';
    const FLASH_REGISTRATION_REQUEST_REJECTED = 'flash.registration_request_rejected';

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
            $this->getEm()->persist($createExpertRequest);
            $this->getEm()->flush();
            $this->addFlash(self::FLASH_CREATE_EXPERT_REQUEST_SENDED, 'Ваш запрос успешно отправлен');

            return $this->redirectToRoute('request_create_expert');
        }

        return $this->render(
            'WantToBecomeExpert/request.html.twig',
            [
                self::KEY_FORM => $form->createView(),
                self::KEY_CATEGORIES => $categories,
            ]
        );
    }

    /**
     * @Route("/want-to-become-an-expert/approve/{id}", name="approve_create_expert")
     *
     * @ParamConverter(name="createExpertRequest", class="AppBundle\Entity\CreateExpertRequest", options={"mapping":{"id":"id"}})
     */
    public function approveAction(Request $request, CreateExpertRequest $createExpertRequest)
    {
        $form = $this->createForm(CreateExpertRequestApproveType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get(CreateExpertRequestApproveType::APPROVE)->isClicked()) {
                $this->get('event_dispatcher')->dispatch(
                    UserEvents::CREATE_EXPERT_APPROVE,
                    new ApproveCreateExpertEvent($createExpertRequest, $this->getUser())
                );
                $this->addFlash(
                    self::FLASH_REGISTRATION_REQUEST_APPROVED,
                    'Заявка на регистрацию пользоветелем '.$createExpertRequest->getEmail().' была одобрена.'
                );
            } elseif ($form->get(CreateExpertRequestApproveType::REJECT)->isClicked()) {
                $this->getEm()->remove($createExpertRequest);
                $this->getEm()->flush();
                $this->addFlash(
                    self::FLASH_REGISTRATION_REQUEST_REJECTED,
                    'Заявка на регистрацию пользоветелем '.$createExpertRequest->getEmail().' была отклонена.'
                );
            }
        }

        return $this->redirectToRoute('category_admin_requests');
    }
}
