<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use AppBundle\SortProduct\SortProduct;
use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\SearchParams\SearchParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    const KEY_POPULAR_PRODUCTS = 'popularProducts';
    const LIMIT_PER_PAGE = 9;
    const KEY_PAGINATION = 'pagination';
    const KEY_CATEGORY = 'category';
    const KEY_FORM_COMMENT = 'formComment';
    const KEY_SIMILAR_PRODUCTS = 'similarProducts';
    const FLASH_SORT_ERRORS = 'sortErrors';
    const KEY_SORT_PRODUCT = 'sortProduct';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SearchParamsType::class, null, ['action' => $this->generateUrl('product_search')]);

        $productRepository = $this->getEm()->getRepository('AppBundle:Product');
        $products = $productRepository->findNew();
        $popularProducts = $productRepository->findPopular();
        // replace this example code with whatever you need
        return $this->render('Index/index.html.twig', [
            self::KEY_PRODUCTS         => $products,
            self::KEY_POPULAR_PRODUCTS => $popularProducts,
            self::KEY_FORM_SEARCH      => $form->createView()]);
    }

    /**
     * @Route("/tovar/search/{page}", name="product_search", defaults={"page"=1})
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $form = $this->createForm(SearchParamsType::class, null, ['action' => $this->generateUrl('product_search')]);
        $form->handleRequest($request);
        $products = [];
        if ($form->isValid()) {
            /** @var SearchParams $searchParams */
            $searchParams = $form->getData();
            $products = $this->get('search_bundle.product_searcher')->find($searchParams);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('Product/search.html.twig', [self::KEY_PAGINATION => $pagination, self::KEY_FORM_SEARCH => $form->createView()]);
    }

    /**
     * @Route("/tovar/{slug}/comment/create", name="product_comment_create")
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
     * @Route("/tovar/{slug}", name="product_detail")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @Security("is_granted('VIEW', product)")
     */
    public function detailAction(Request $request, Product $product)
    {
        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('product_comment_create', ['slug' => $product->getSlug()])
        ]);
        $similarProducts = $this->getEm()->getRepository('AppBundle:Product')->findSimilar($product);

        return $this->render('Product/detail.html.twig', [
            self::KEY_PRODUCT          => $product,
            self::KEY_SIMILAR_PRODUCTS => $similarProducts,
            self::KEY_FORM_COMMENT     => $formComment->createView()
        ]);
    }

    /**
     * @Route("/rubric/{slug}/{page}/{sortField}/{sortDirection}/{status}", name="product_list", defaults={"page"=1, "sortField"="minPrice", "sortDirection"="ASC", "status" = null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function listAction(Request $request, Category $category, $page, $sortField, $sortDirection, $status)
    {
        $sortProduct = new SortProduct();
        $sortProduct->setFieldName($sortField)->setDirection($sortDirection);

        $validator = $this->get('validator');
        $errors = $validator->validate($sortProduct);

        $isEnabled = ($status == 'free') ? false : true;
        if (count($errors) > 0) {
            $query = $this->getEm()->getRepository('AppBundle:Product')->findByCategoryQuery($category);
            $this->addFlash(self::FLASH_SORT_ERRORS, (string)$errors);
        } else {
            $query = $this->getEm()->getRepository('AppBundle:Product')->findByCategoryQuery($category, $sortProduct, $isEnabled);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $template = 'Product/list.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Product/listPart.html.twig';
        }
        return $this->render($template, [self::KEY_PAGINATION   => $pagination,
                                         self::KEY_CATEGORY     => $category,
                                         self::KEY_SORT_PRODUCT => $sortProduct]);
    }
}
