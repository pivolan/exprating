<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use AppBundle\Form\SortProductType;
use AppBundle\SortProduct\SortProduct;
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
    const FLASH_SORT_ERRORS = 'sort_errors';
    const KEY_SORT_PRODUCT = 'sortProduct';


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
     * @Route("/rubric/{slug}/{page}/{sortField}/{sortDirection}", name="product_list", defaults={"page"=1, "sortField"="minPrice", "sortDirection"="ASC"})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function listAction(Category $category, $page, $sortField, $sortDirection)
    {
        $sortProduct = new SortProduct();
        $sortProduct->setFieldName($sortField)->setDirection($sortDirection);

        $validator = $this->get('validator');
        $errors = $validator->validate($sortProduct);

        if (count($errors) > 0) {
            $query = $this->getEm()->getRepository('AppBundle:Product')->findByCategoryQuery($category);
            $this->addFlash(self::FLASH_SORT_ERRORS, (string)$errors);
        } else {
            $query = $this->getEm()->getRepository('AppBundle:Product')->findByCategoryQuery($category, $sortProduct);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('product/list.html.twig', [self::KEY_PAGINATION   => $pagination,
                                                        self::KEY_CATEGORY     => $category,
                                                        self::KEY_SORT_PRODUCT => $sortProduct]);
    }
}
