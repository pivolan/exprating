<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    const KEY_PRODUCT = 'product';
    const LIMIT_PER_PAGE = 9;
    const KEY_PAGINATION = 'pagination';
    const KEY_CATEGORY = 'category';
    const KEY_FORM_COMMENT = 'formComment';
    const KEY_SIMILAR_PRODUCTS = 'similarProducts';


    /**
     * @Route("/product/{slug}/comment/create", name="product_comment_create")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function commentCreateAction(Request $request, Product $product)
    {
        $comment = new Comment();
        if ($request->getUser()) {
            $comment->setUser($request->getUser());
        }
        $comment->setProduct($product);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager = $this->getEm();
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('product_detail', ['slug' => $comment->getProduct()->getSlug()]);
    }

    /**
     * @Route("/product/{slug}", name="product_detail")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function detailAction(Request $request, Product $product)
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('product_comment_create', ['slug' => $product->getSlug()])
        ]);
        $similarProducts = $this->getEm()->getRepository('AppBundle:Product')->findSimilar($product);

        return $this->render('product/detail.html.twig', [
            self::KEY_PRODUCT          => $product,
            self::KEY_SIMILAR_PRODUCTS => $similarProducts,
            self::KEY_FORM_COMMENT     => $formComment->createView()
        ]);
    }

    /**
     * @Route("/rubric/{slug}/{page}", name="product_list", defaults={"page"=1})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function listAction(Request $request, Category $category, $page)
    {
        $query = $this->getEm()->getRepository('AppBundle:Product')->findByCategoryQuery($category);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('product/list.html.twig', [self::KEY_PAGINATION => $pagination,
                                                        self::KEY_CATEGORY   => $category]);
    }
}
