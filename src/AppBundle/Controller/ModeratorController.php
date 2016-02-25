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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_MODERATOR')")
 */
class ModeratorController extends BaseController
{
    const KEY_MESSAGES = 'messages';
    const KEY_COMMENT = 'comment';
    const KEY_FORMS = 'forms';

    /**
     * @Route("/moderator/comments/{page}", defaults={"page":1}, name="moderator_comments")
     * @param Request $request
     * @param         $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentsAction(Request $request, $page)
    {
        $query = $this->getEm()->getRepository('AppBundle:Comment')->findDisabledQuery();
        $paginator = $this->get('knp_paginator');
        /** @var Comment[] $pagination */
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $forms = [];
        foreach ($pagination as $comment) {
            $form = $this->createForm(ModeratorCommentType::class,
                $comment,
                ['action' => $this->generateUrl('moderator_decision', ['id' => $comment->getId()])]
            );
            $forms[] = $form->createView();
        }
        return $this->render('Moderator/messages.html.twig', [self::KEY_PAGINATION => $pagination, self::KEY_FORMS => $forms]);
    }

    /**
     * @Route("/moderator/feedbacks/{page}", name="moderator_feedbacks", defaults={"page": 1})
     */
    public function feedbacksAction($page)
    {
        return $this->render('Moderator/feedbacks.html.twig');
    }

    /**
     * @Route("/moderator/decision/{id}", name="moderator_decision")
     * @ParamConverter(name="comment", class="AppBundle\Entity\Comment", options={"mapping":{"id":"id"}})
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

    public function _menuAction()
    {
        $commentCount = $this->getEm()->getRepository('AppBundle:Comment')->newCount();
        $feedbackCount = $this->getEm()->getRepository('AppBundle:Feedback')->newCount();

        return $this->render('Moderator/_menu.html.twig',
            ['commentCount'  => $commentCount,
             'feedbackCount' => $feedbackCount
            ]);
    }
}