<?php

/**
 * Date: 15.02.16
 * Time: 19:09.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\ProductEditedEvent;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Form\ProductChooseCategoryType;
use AppBundle\Security\ProductVoter;
use AppBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditController extends BaseController
{
    const FLASH_EXPERTISE_ERROR_MESSAGE = 'flash.expertise.error_message';
    const FLASH_EXPERTISE_MESSAGE = 'flash.expertise.message';
    const KEY_HISTORY_LOGS = 'historyLogs';

    /**
     * @Route("/tovar/{slug}/edit/{page}", name="product_edit", defaults={"page":1})
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     *
     * @param Request $request
     * @param Product $product
     *
     * @Security("is_granted('EXPERTISE', product)")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product, $page)
    {
        /** @var User $expert */
        $expert = $this->getUser();
        if ($this->isGranted(ProductVoter::RESERVE, $product)) {
            //Резервируем товар за текущим экспертом
            $event = new ProductReservationEvent($product, $expert);
            $this->get('event_dispatcher')
                ->dispatch(ProductEvents::RESERVATION, $event);
        }
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('event_dispatcher')->dispatch(
                ProductEvents::EDITED,
                new ProductEditedEvent($product, $this->getUser())
            );
            $this->getEm()->flush();
            //Если нажал кнопку опубликовать, тогда запускаем событие публикации
            $isClicked = $form->get(ProductType::PUBLISH_SUBMIT)->isClicked();
            if ($isClicked) {
                if ($this->isGranted(ProductVoter::PUBLISH, $product)) {
                    $this->get('event_dispatcher')
                        ->dispatch(ProductEvents::PUBLISH_REQUEST, new ProductPublishRequestEvent($product));
                    $this->addFlash(
                        self::FLASH_EXPERTISE_MESSAGE,
                        'Ваш обзор отправлен на премодерацию куратором. О его решении вы будете уведомлены по email'
                    );
                } else {
                    throw new HttpException(
                        403,
                        'Невозможно опубликовать. Обзор уже был опубликован, или ожидает решения куратора.'
                    );
                }
            }
            $this->addFlash(self::FLASH_EXPERTISE_MESSAGE, 'Изменения сохранены');

            return $this->redirect($request->getRequestUri());
        } elseif ($form->getErrors(true)->count()) {
            $this->addFlash(self::FLASH_EXPERTISE_ERROR_MESSAGE, 'Ошибка заполнения данных');
        }
        $template = 'Product/edit.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Product/editPart.html.twig';
        }

        $historyLogsQuery = $this->getEm()->getRepository('AppBundle:ProductEditHistory')->getQueryByProduct($product);
        $paginator = $this->get('knp_paginator');
        $historyLogs = $paginator->paginate(
            $historyLogsQuery,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );


        return $this->render(
            $template,
            [
                self::KEY_PRODUCT      => $product,
                self::KEY_FORM         => $form->createView(),
                self::KEY_HISTORY_LOGS => $historyLogs,
            ]
        );
    }

    /**
     * @Route("/tovar/{slug}/choose_category", name="product_choose_category")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     * @Security("is_granted('EXPERTISE', product)")
     * @param Request $request
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chooseCategoryAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductChooseCategoryType::class, $product);
        $form->handleRequest($request);

        $categories = $this->getEm()->getRepository('AppBundle:Category')->getForJsTree();

        return $this->render(
            'Product/chooseCategory.html.twig',
            [self::KEY_PRODUCT => $product, self::KEY_FORM => $form->createView(), self::KEY_CATEGORIES => $categories]
        );
    }
}
