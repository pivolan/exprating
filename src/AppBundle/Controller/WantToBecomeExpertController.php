<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RegistrationRequest;
use AppBundle\Entity\User;
use AppBundle\Event\User\ApproveRegistrationEvent;
use AppBundle\Event\User\RegistrationRequestEvent;
use AppBundle\Event\User\UserEvents;
use AppBundle\Form\RegistrationRequestApproveType;
use AppBundle\Form\RegistrationRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WantToBecomeExpertController extends BaseController
{
    const KEY_CREATE_EXPERT_REQUEST = 'registrationRequest';
    const FLASH_CREATE_EXPERT_REQUEST_SENDED = 'flash.registration_request_sended';
    const FLASH_REGISTRATION_REQUEST_APPROVED = 'flash.registration_request_approved';
    const FLASH_REGISTRATION_REQUEST_REJECTED = 'flash.registration_request_rejected';

    /**
     * @Route("/want-to-become-an-expert/", name="request_create_expert")
     */
    public function requestAction(Request $request)
    {
        $registrationRequest = new RegistrationRequest();

        $form = $this->createForm(RegistrationRequestType::class, $registrationRequest);

        $form->handleRequest($request);

        $categories = $this->getEm()->getRepository('AppBundle:Category')->getForJsTree();

        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                UserEvents::REGISTRATION_REQUEST,
                new RegistrationRequestEvent($registrationRequest)
            );
            $this->addFlash(self::FLASH_CREATE_EXPERT_REQUEST_SENDED, 'Ваш запрос успешно отправлен');

            return $this->render('WantToBecomeExpert/requestSuccess.html.twig');
        }

        return $this->render(
            'WantToBecomeExpert/request.html.twig',
            [
                self::KEY_FORM       => $form->createView(),
                self::KEY_CATEGORIES => $categories,
            ]
        );
    }

    /**
     * @Route("/want-to-become-an-expert/approve/{id}/", name="approve_create_expert")
     *
     * @ParamConverter(name="registrationRequest", class="AppBundle\Entity\RegistrationRequest",
     *     options={"mapping":{"id":"id"}})
     */
    public function approveAction(Request $request, RegistrationRequest $registrationRequest)
    {
        $form = $this->createForm(RegistrationRequestApproveType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get(RegistrationRequestApproveType::APPROVE)->isClicked()) {
                $this->get('event_dispatcher')->dispatch(
                    UserEvents::REGISTRATION_APPROVE,
                    new ApproveRegistrationEvent($registrationRequest, $this->getUser())
                );
                $this->addFlash(
                    self::FLASH_REGISTRATION_REQUEST_APPROVED,
                    'Заявка на регистрацию пользоветелем '.$registrationRequest->getEmail().' была одобрена.'
                );
            } elseif ($form->get(RegistrationRequestApproveType::REJECT)->isClicked()) {
                $this->getEm()->remove($registrationRequest);
                $this->getEm()->flush();
                $this->addFlash(
                    self::FLASH_REGISTRATION_REQUEST_REJECTED,
                    'Заявка на регистрацию пользоветелем '.$registrationRequest->getEmail().' была отклонена.'
                );
            }
        }

        return $this->redirectToRoute('category_admin_requests');
    }
}
