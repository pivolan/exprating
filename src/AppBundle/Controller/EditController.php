<?php
/**
 * Date: 15.02.16
 * Time: 19:09
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use AppBundle\ProductFilter\ProductFilter;
use Exprating\ExpertBundle\Form\ProductType;
use Exprating\SearchBundle\Form\SearchParamsType;
use Exprating\SearchBundle\SearchParams\SearchParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EditController extends BaseController
{
    /**
     * @Route("/tovar/{slug}/edit", name="product_edit")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @param Request $request
     * @param Product $product
     *
     * @Security("is_granted('EXPERTISE', product)")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
        }
        return $this->render('Product/edit.html.twig', [self::KEY_PRODUCT => $product, self::KEY_FORM => $form->createView()]);
    }

    public function publishAction()
    {

    }

} 