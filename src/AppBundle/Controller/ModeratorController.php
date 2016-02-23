<?php
/**
 * Date: 23.02.16
 * Time: 23:02
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use AppBundle\Event\ProductCommentedEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Form\ModeratorCommentType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("is_granted('ROLE_MODERATOR')")
 */
class ModeratorController extends BaseController
{
    const KEY_MESSAGES = 'messages';
    const KEY_COMMENT = 'comment';

    /**
     * @Route("/moderator/messages/{page}", defaults={"page":1}, name="moderator_messages")
     * @param Request $request
     * @param         $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messagesAction(Request $request, $page)
    {
        $query = $this->getEm()->getRepository('AppBundle:Comment')->findDisabledQuery();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('Moderator/messages.html.twig', [self::KEY_PAGINATION => $pagination]);
    }

    /**
     * @Route("/moderator/decision/{id}", name="moderator_decision")
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     * @param Request $request
     * @param Comment $comment
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decisionAction(Request $request, Comment $comment)
    {
        $form = $this->createForm(ModeratorCommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $event = new ProductCommentedEvent($comment);
            $this->get('event_dispatcher')->dispatch(ProductEvents::COMMENTED, $event);
            $this->addFlash(self::FLASH_MESSAGE, $event->getMessage());
        }
        return $this->render('Moderator/decision.html.twig', [self::KEY_FORM => $form, self::KEY_COMMENT => $comment]);
    }
}