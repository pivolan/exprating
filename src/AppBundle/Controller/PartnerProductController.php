<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Event\ProductCommentedEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Form\CommentType;
use AppBundle\ProductFilter\ProductFilter;
use Exprating\ImportXmlBundle\Entity\Offer;
use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\SearchParams\SearchParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

    /**
     * @Route("/partner/products", name="partner_product_list")
     */
    public function listAction(Request $request, $product)
    {
        $emImportXml = $this->get('doctrine.orm.import_xml_entity_manager');
        /** @var Offer[] $partnerProducts */
        $partnerProducts = $emImportXml->getRepository('ExpratingImportXmlBundle:Offer')->findBy([], null, 30);
        $products = $this->getEm()->getRepository('AppBundle:Product')->findOneBy(['slug' => $product]);
        $images = $products->getImportedImages();
        $output = [
            self::KEY_PARTNER_PRODUCTS =>$partnerProducts,
            self::KEY_PRODUCT => $product,
            self::KEY_PARTNER_IMAGES => $images
        ];
        return $this->render('PartnerProduct/list.html.twig', $output);
    }
}
