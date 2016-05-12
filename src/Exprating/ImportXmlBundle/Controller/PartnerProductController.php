<?php

namespace Exprating\ImportXmlBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Event\ProductCommentedEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductSaveQueryStringEvent;
use AppBundle\Form\CommentType;
use AppBundle\ProductFilter\ProductFilter;
use Exprating\ImportXmlBundle\Dto\SearchInput;
use Exprating\ImportXmlBundle\Entity\PartnerProduct;
use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\Dto\SearchParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PartnerProductController extends BaseController
{
    const KEY_POPULAR_PRODUCTS = 'popularProducts';
    const KEY_FORM_COMMENT = 'formComment';
    const KEY_SIMILAR_PRODUCTS = 'similarProducts';
    const FLASH_SORT_ERRORS = 'sortErrors';
    const KEY_SORT_PRODUCT = 'productFilter';
    const FLASH_COMMENT_MESSAGE = 'flash.comment.message';
    const KEY_COMMENTS = 'comments';
    const KEY_PARTNER_PRODUCTS = 'partnerProducts';
    const KEY_PARTNER_IMAGES = 'partnerImages';
    const KEY_ERRORS = 'errors';
    const KEY_SEARCH_INPUT = 'searchInput';

    /**
     * @Route("/partner/products/{slug}/", name="partner_product_list")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function searchAction(Request $request, Product $product)
    {
        /** @var SearchInput $searchInput */
        $searchInput = $this->get('serializer')->denormalize($request->request->all(), SearchInput::class);
        $validator = $this->get('validator');
        $errors = $validator->validate($searchInput);
        if (count($errors) > 0) {
            return $this->render(
                'PartnerProduct/saveSameProductsQueryString.error.html.twig',
                [self::KEY_ERRORS => $errors]
            );
        }

        /** @var PartnerProduct[] $partnerProducts */
        $partnerProducts = $this->getEm()->getRepository('ExpratingImportXmlBundle:PartnerProduct')->findBy(
            ['name' => $searchInput->getSearch()],
            null,
            30
        );

        return $this->render(
            'PartnerProduct/list.html.twig',
            [
                self::KEY_PARTNER_PRODUCTS => $partnerProducts,
                self::KEY_PRODUCT          => $product,
            ]
        );
    }

    /**
     * @Route("/product/{slug}/query/save/", name="product_query_save")
     * @param Request $request
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     *
     * @Security("is_granted('EXPERTISE', product)")
     * @return Response
     */
    public function saveSameProductsQueryStringAction(Request $request, Product $product)
    {
        /** @var SearchInput $searchInput */
        $searchInput = $this->get('serializer')->denormalize($request->request->all(), SearchInput::class);
        $validator = $this->get('validator');
        $errors = $validator->validate($searchInput);
        if (count($errors) > 0) {
            return $this->render(
                'PartnerProduct/saveSameProductsQueryString.error.html.twig',
                [self::KEY_ERRORS => $errors]
            );
        }
        try {
            $this->get('event_dispatcher')->dispatch(
                ProductEvents::SAVE_QUERY_STRING,
                new ProductSaveQueryStringEvent($searchInput, $product, $this->getUser())
            );
        } catch (\Exception $e) {
            return $this->render(
                'PartnerProduct/saveSameProductsQueryString.error.html.twig',
                [self::KEY_ERRORS => [$e->getMessage()]]
            );
        }

        return $this->render(
            'PartnerProduct/saveSameProductsQueryString.success.html.twig',
            [
                self::KEY_SEARCH_INPUT => $searchInput,
                self::KEY_PRODUCT      => $product,
            ]
        );
    }
}
