<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Event\ProductCommentedEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Form\CommentType;
use AppBundle\ProductFilter\ProductFilter;
use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\SearchParams\SearchParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexController extends BaseController
{
    const KEY_POPULAR_PRODUCTS = 'popularProducts';
    const KEY_FORM_COMMENT = 'formComment';
    const KEY_SIMILAR_PRODUCTS = 'similarProducts';
    const FLASH_SORT_ERRORS = 'sortErrors';
    const KEY_SORT_PRODUCT = 'productFilter';
    const FLASH_COMMENT_MESSAGE = 'flash.comment.message';
    const KEY_COMMENTS = 'comments';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $form = $this->createForm(SearchParamsType::class, null, ['action' => $this->generateUrl('product_search')]);

        $productRepository = $this->getEm()->getRepository('AppBundle:Product');
        $products = $productRepository->findNew();
        $popularProducts = $productRepository->findPopular();

        // replace this example code with whatever you need
        return $this->render(
            'Index/index.html.twig',
            [
                self::KEY_PRODUCTS         => $products,
                self::KEY_POPULAR_PRODUCTS => $popularProducts,
                self::KEY_FORM_SEARCH      => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/tovar/search/{page}", name="product_search", defaults={"page"=1})
     *
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

        return $this->render(
            'Product/search.html.twig',
            [self::KEY_PAGINATION => $pagination, self::KEY_FORM_SEARCH => $form->createView()]
        );
    }

    /**
     * @Route("/tovar/{slug}/comment/create", name="product_comment_create")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function commentCreateAction(Request $request, Product $product)
    {
        $comment = new Comment();
        $this->getEm()->persist($comment);
        if ($request->getUser()) {
            $comment->setUser($this->getUser());
        }
        $comment->setProduct($product);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(ProductEvents::COMMENTED, new ProductCommentedEvent($comment));
            $this->addFlash(self::FLASH_COMMENT_MESSAGE, 'Ваш комментарий будет опубликован после модерации');
        } elseif ($form->isSubmitted()) {
            $errors = $form->getErrors(true);
            $this->addFlash(self::FLASH_COMMENT_MESSAGE, print_r($errors, true));
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
        $formComment = $this->createForm(
            CommentType::class,
            $comment,
            [
                'action' => $this->generateUrl('product_comment_create', ['slug' => $product->getSlug()]),
            ]
        );
        $similarProducts = $this->getEm()->getRepository('AppBundle:Product')->findSimilar($product);

        $template = 'Product/detail.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Product/detailPart.html.twig';
        }

        $comments = $this->getEm()->getRepository('AppBundle:Comment')->findByProductEnabled($product);

        return $this->render(
            $template,
            [
                self::KEY_PRODUCT          => $product,
                self::KEY_SIMILAR_PRODUCTS => $similarProducts,
                self::KEY_FORM_COMMENT     => $formComment->createView(),
                self::KEY_COMMENTS         => $comments,
            ]
        );
    }

    /**
     * @Route("/rubric/{peopleGroup}/{slug}/{page}/{sortField}/{sortDirection}/{status}", name="product_list",
     *     requirements = {"peopleGroup": "(dlya-zhenshchin)|(dlya-muzhchin)|(dlya-detey)|(dlya-vseh)"},
     *     defaults={"page"=1, "sortField"="minPrice", "sortDirection"="ASC", "status" = null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     * @ParamConverter(name="peopleGroup", class="AppBundle\Entity\PeopleGroup",
     *     options={"mapping":{"peopleGroup":"slug"}})
     */
    public function listAction(Request $request)
    {
        /** @var ProductFilter $productFilter */
        $productFilter = $this->get('serializer')->denormalize($request->attributes->all(), ProductFilter::class);
        $productFilter->setCurator($this->getUser());
        $validator = $this->get('validator');
        $errors = $validator->validate($productFilter);
        if (count($errors) > 0) {
            $this->addFlash(self::FLASH_SORT_ERRORS, (string)$errors);
            throw new HttpException(403, (string)$errors);
        }

        $query = $this->getEm()->getRepository('AppBundle:Product')->findByFilterQuery($productFilter);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($productFilter->page, 1),
            self::LIMIT_PER_PAGE
        );
        $template = 'Product/list.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Product/listPart.html.twig';
        }

        return $this->render(
            $template,
            [
                self::KEY_PAGINATION   => $pagination,
                self::KEY_CATEGORY     => $productFilter->getCategory(),
                self::KEY_SORT_PRODUCT => $productFilter,
            ]
        );
    }
}
