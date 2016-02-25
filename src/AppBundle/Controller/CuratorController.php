<?php
/**
 * Date: 20.02.16
 * Time: 1:36
 */

namespace AppBundle\Controller;


use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use AppBundle\Event\DecisionCreateEvent;
use AppBundle\Event\ProductChangeExpertEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Form\DecisionType;
use AppBundle\Form\ProductChangeExpertType;
use AppBundle\ProductFilter\ProductFilter;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CuratorController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_EXPERT_CURATOR')")
 */
class CuratorController extends BaseController
{
    const FLASH_DECISION_INFO = 'flash.curator.decision.info';

    /**
     * @Route("/wait_list/{page}", name="curator_wait_list", defaults={"page":1})
     */
    public function waitListAction(Request $request, $page)
    {
        $query = $this->getEm()->getRepository('AppBundle:CuratorDecision')->waitByCurator($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $template = 'Curator/wait_list.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Curator/wait_listPart.html.twig';
        }
        return $this->render($template, [self::KEY_PAGINATION => $pagination]);
    }

    /**
     * @param Request $request
     * @param         $product
     *
     * @Route("/curator/decision/{slug}", name="curator_decision")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @Security("is_granted('MODERATE', product)")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decisionEditAction(Request $request, Product $product)
    {
        /** @var CuratorDecision $decision */
        $decision = $this->getEm()->getRepository('AppBundle:CuratorDecision')
            ->waitByCuratorByProduct($this->getUser(), $product)->getSingleResult();
        $decision->setProduct($product)->setCurator($this->getUser());

        $form = $this->createForm(DecisionType::class, $decision,
            ['action' => $this->generateUrl('curator_decision', ['slug' => $product->getSlug()])]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(ProductEvents::DECISION, new DecisionCreateEvent($decision));
            if ($decision->getStatus() == CuratorDecision::STATUS_APPROVE) {
                $this->addFlash(self::FLASH_DECISION_INFO, 'Обзор успешно опубликован');
            } else {
                $this->addFlash(self::FLASH_DECISION_INFO, 'Обзор отклонен');
            }
            return $this->redirectToRoute('product_detail', ['slug' => $product->getSlug()]);
        }
        return $this->render('Curator/decision_form.html.twig', [self::KEY_FORM => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param         $product
     *
     * @Route("/change_expert/{slug}", name="curator_product_change_expert")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @Security("is_granted('CHANGE_EXPERT', product)")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeProductExpertAction(Request $request, Product $product)
    {
        $currentExpert = $product->getExpertUser();
        $form = $this->createForm(ProductChangeExpertType::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('event_dispatcher')
                ->dispatch(ProductEvents::CHANGE_EXPERT,
                    new ProductChangeExpertEvent(
                        $product,
                        $product->getExpertUser(),
                        $currentExpert,
                        $this->getUser()
                    )
                );
            $this->addFlash(self::FLASH_MESSAGE, 'Изменения сохранены');
            return $this->redirect($request->getRequestUri());
        } elseif ($form->getErrors(true)->count()) {
            $this->addFlash(self::FLASH_ERROR_MESSAGE, 'Ошибка заполнения данных');
        }
        $template = 'Product/editChangeExpert.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Product/editChangeExpertPart.html.twig';
        }
        return $this->render($template, [self::KEY_PRODUCT => $product, self::KEY_FORM => $form->createView()]);
    }

    /**
     * @Route("/curator/experts/{page}", name="curator_experts", defaults={"page":1})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expertsAction(Request $request, $page)
    {
        $query = $this->getUser()->getExperts();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('Curator/experts.html.twig', [self::KEY_PAGINATION => $pagination]);
    }

    /**
     * @Route("/curator/decisions/{page}/{slug}", name="curator_decisions", defaults={"page": 1, "slug": null})
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function decisionsAction($page, Product $product = null)
    {
        $query = $this->getEm()->getRepository('AppBundle:CuratorDecision')
            ->byCurator($this->getUser(), $product);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        return $this->render('Curator/decisions.html.twig',
            [self::KEY_PAGINATION => $pagination, self::KEY_PAGE => $page, self::KEY_USER => $this->getUser()
             , self::KEY_PRODUCT  => $product]);
    }

    public function _menuAction()
    {
        $waitItemsCount = $this->getEm()->getRepository('AppBundle:CuratorDecision')->countNew($this->getUser());

        return $this->render('Curator/_menu.html.twig',
            ['waitItemsCount' => $waitItemsCount]);
    }
}