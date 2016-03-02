<?php

/**
 * Date: 15.02.16
 * Time: 19:09.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Security\ProductVoter;
use Exprating\CharacteristicBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditController extends BaseController
{
    const FLASH_EXPERTISE_ERROR_MESSAGE = 'flash.expertise.error_message';
    const FLASH_EXPERTISE_MESSAGE = 'flash.expertise.message';

    /**
     * @Route("/tovar/{slug}/edit", name="product_edit")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     *
     * @param Request $request
     * @param Product $product
     *
     * @Security("is_granted('EXPERTISE', product)")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
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
            $this->getEm()->flush();
            //Если нажал кнопку опубликовать, тогда запускаем событие публикации
            $isClicked = $form->get(ProductType::PUBLISH_SUBMIT)->isClicked();
            if ($isClicked) {
                if ($this->isGranted(ProductVoter::PUBLISH, $product)) {
                    $this->get('event_dispatcher')
                        ->dispatch(ProductEvents::PUBLISH_REQUEST, new ProductPublishRequestEvent($product));
                    $this->addFlash(self::FLASH_EXPERTISE_MESSAGE, 'Ваш обзор отправлен на премодерацию куратором. О его решении вы будете уведомлены по email');
                } else {
                    throw new HttpException(403, 'Невозможно опубликовать. Обзор уже был опубликован, или ожидает решения куратора.');
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

        return $this->render($template, [self::KEY_PRODUCT => $product, self::KEY_FORM => $form->createView()]);
    }
}
