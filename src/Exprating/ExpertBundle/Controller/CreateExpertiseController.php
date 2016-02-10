<?php

namespace Exprating\ExpertBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Exprating\ExpertBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Form\SearchTypeFabric;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CreateExpertiseController extends BaseController
{
    const KEY_CURRENT_CATEGORY = 'currentCategory';
    const FLASH_REVIEW_COMPLETE = 'review_complete';

    /**
     * @Route("/cabinet/new-review/categories", name="expert_categories")
     */
    public function categoryListAction()
    {
        return $this->render('ExpratingExpertBundle:CreateExpertise:category_list.html.twig', [
            // ...
        ]);
    }

    /**
     * @Route("/cabinet/new-review/{slug}/products", name="expert_category_products")
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function productListAction(Request $request, Category $category)
    {
        /** @var User $expert */
        $expert = $this->getUser();
        if (!$expert->getCategories()->contains($category)) {
            throw new AccessDeniedHttpException();
        }
        $form = (new SearchTypeFabric())->create($this->get('form.factory'), $category);

        $form->handleRequest($request);
        $products = [];
        if ($form->isValid()) {
            /** @var CommonProductSearch $searchParams */
            $searchParams = $form->getData();
            $products = $this->getEm()->getRepository('AppBundle:Product')->findByCharacteristicsQuery($searchParams, $category)->getResult();
        }

        return $this->render('ExpratingExpertBundle:CreateExpertise:product_list.html.twig',
            [self::KEY_CURRENT_CATEGORY => $category,
             self::KEY_FORM             => $form->createView(),
             self::KEY_PRODUCTS         => $products]);
    }

    /**
     * @Route("/cabinet/new-review/products/{slug}", name="expert_product")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function createReviewAction(Request $request, Product $product)
    {
        if (!$this->getUser()->getCategories()->contains($product->getCategory())) {
            throw new AccessDeniedHttpException();
        }
        if ($product->getIsEnabled()) {
            throw new AccessDeniedHttpException();
        }
        $product->setExpertUser($this->getUser());
        $this->getEm()->flush();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEm()->flush();
            return $this->redirectToRoute('expert_complete', ['slug' => $product->getSlug()]);
        }

        return $this->render('ExpratingExpertBundle:CreateExpertise:create_review.html.twig', [
            self::KEY_PRODUCT => $product,
            self::KEY_FORM    => $form->createView()
        ]);
    }

    /**
     * @Route("/cabinet/new-review/complete/{slug}", name="expert_complete")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function completeAction(Product $product)
    {
        return $this->render('ExpratingExpertBundle:CreateExpertise:complete.html.twig', [
            self::KEY_PRODUCT => $product
        ]);
    }

}
