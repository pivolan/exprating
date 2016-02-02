<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends BaseController
{
    /**
     * @Route("/comment/create", name="comment_create")
     */
    public function createAction(Request $request)
    {
        $comment = new Comment();
        if ($request->getUser()) {
            $comment->setUser($request->getUser());
        }
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager = $this->getEm();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('product_show', ['slug' => $comment->getProduct()->getSlug()]);
        }
        return $this->render(':comment:create.html.twig', [
            'form' => $form
        ]);
    }

}
