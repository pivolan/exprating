<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invite;
use AppBundle\Entity\User;
use AppBundle\Event\Invite\Exception\RequestInviteRightsException;
use AppBundle\Event\Invite\InviteActivateEvent;
use AppBundle\Event\Invite\InviteApproveRightsEvent;
use AppBundle\Event\Invite\InviteEvents;
use AppBundle\Event\Invite\InviteRequestRightsEvent;
use AppBundle\Event\Invite\InviteSendEvent;
use AppBundle\Form\InviteType;
use AppBundle\Form\UserCompleteType;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class InviteController extends BaseController
{
    const KEY_INVITE = 'invite';
    const FLASH_INVITE_SENDED = 'flash.invite_sended';
    const FLASH_APPROVED_RIGHTS = 'flash.approved_rights';
    const FLASH_REQUEST_RIGHTS_SENDED = 'flash.request_rights_sended';
    const FLASH_REQUEST_RIGHTS_FAIL = 'flash.request_rights_fail';

    /**
     * @Route("/invite", name="invite")
     * @Security("is_granted('ROLE_EXPERT')")
     */
    public function inviteAction(Request $request)
    {
        $invite = new Invite();
        $invite->setCurator($this->getUser());

        $form = $this->createForm(InviteType::class, $invite);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(InviteEvents::SEND, new InviteSendEvent($invite));
            $this->addFlash(self::FLASH_INVITE_SENDED, 'Приглашение успешно отправлено на e-mail '.$invite->getEmail());

            return $this->redirect($request->getUri());
        }

        return $this->render(
            'Invite/invite.html.twig',
            [self::KEY_FORM => $form->createView()]
        );
    }

    /**
     * @Route("/invite/{hash}", name="invite_activate")
     * @ParamConverter(name="invite", class="AppBundle\Entity\Invite", options={"mapping":{"hash":"hash"}})
     * @Security("is_granted('ACTIVATE_INVITE', invite)")
     */
    public function inviteActivateAction(Request $request, Invite $invite)
    {
        $this->get('event_dispatcher')->dispatch(
            InviteEvents::ACTIVATE,
            new InviteActivateEvent($invite)
        );
        $user = $invite->getExpert();
        $form = $this->createForm(UserCompleteType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $response = $this->redirectToRoute('experts_detail', ['username' => $user->getUsername()]);
            $event = new \AppBundle\Event\Invite\InviteCompleteRegistrationEvent($user, $request, $response);
            $this->get('event_dispatcher')->dispatch(InviteEvents::COMPLETE_REGISTRATION, $event);

            return $response;
        }

        return $this->render(
            'Invite/inviteComplete.html.twig',
            [
                self::KEY_FORM   => $form->createView(),
                self::KEY_INVITE => $invite,
            ]
        );
    }

    /**
     * @Route("/request/rights/invite", name="invite_request_rights")
     * @Security("is_granted('ROLE_EXPERT')")
     */
    public function requestInviteRightsAction()
    {
        $expert = $this->getUser();
        $event = new InviteRequestRightsEvent($expert);
        try {
            $this->get('event_dispatcher')->dispatch(InviteEvents::REQUEST_RIGHTS, $event);
            $this->addFlash(self::FLASH_REQUEST_RIGHTS_SENDED, 'Ваша заявка отправлена куратору');
        } catch (RequestInviteRightsException $e) {
            $this->addFlash(self::FLASH_REQUEST_RIGHTS_FAIL, $e->getMessage());
        }

        return $this->redirectToRoute('invite');
    }

    /**
     * @Route("/approve/rights/invite/{username}", name="invite_approve_rights")
     * @ParamConverter(name="expert", class="AppBundle\Entity\User", options={"mapping":{"username":"username"}})
     * @Security("is_granted('ADD_ROLE_CURATOR', expert)")
     */
    public function approveRightsAction(User $expert)
    {
        $curator = $this->getUser();
        $event = new InviteApproveRightsEvent($expert, $curator);
        $this->get('event_dispatcher')->dispatch(InviteEvents::APPROVE_RIGHTS, $event);
        $this->addFlash(self::FLASH_APPROVED_RIGHTS, 'Вы разрешили пользователю приглашать новых экспертов.');

        return $this->redirectToRoute('experts_detail', ['username' => $expert->getUsername()]);
    }
}
