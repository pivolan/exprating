<?php
/**
 * Date: 15.02.16
 * Time: 19:09
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductPublishRequestEvent;
use AppBundle\Event\ProductReservationEvent;
use AppBundle\Security\ProductVoter;
use Exprating\ExpertBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EditController extends BaseController
{
    const FLASH_EXPERTISE_MESSAGE = 'flash.expertise.message';

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
        /** @var User $expert */
        $expert = $this->getUser();
        if ($this->isGranted(ProductVoter::RESERVE, $product)) {
            //Резервируем товар за текущим экспертом
            $this->get('debug.event_dispatcher')
                ->dispatch(ProductEvents::RESERVATION, new ProductReservationEvent($product, $expert));
        }
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getEm()->flush();
            //Если нажал кнопку опубликовать, тогда запускаем событие публикации
            if ($form->get(ProductType::PUBLISH_SUBMIT)->isSubmitted() &&
                $this->isGranted(ProductVoter::PUBLISH, $product)
            ) {
                $this->get('debug.event_dispatcher')
                    ->dispatch(ProductEvents::PUBLISH_REQUEST, new ProductPublishRequestEvent($product));
                $this->addFlash(self::FLASH_EXPERTISE_MESSAGE, 'Обзор отправлен на модерацию куратору');
            }
            $this->addFlash(self::FLASH_EXPERTISE_MESSAGE, 'Изменения сохранены');
            return $this->redirect($request->getRequestUri());
        }
        $template = 'Product/edit.html.twig';
        if($request->isXmlHttpRequest())
        {
            $template = 'Product/editPart.html.twig';
        }
        return $this->render($template, [self::KEY_PRODUCT => $product, self::KEY_FORM => $form->createView()]);
    }

    public function publishAction()
    {

    }

} 