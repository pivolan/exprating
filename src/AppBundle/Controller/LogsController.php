<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Event\ProductEvents;
use AppBundle\Event\ProductVisitEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogsController extends BaseController
{
    const KEY_EXPERT_OPINIONS = 'expertOpinions';
    const LIMIT_OPINIONS_PER_PAGE = 5;
    const KEY_EXPERT = 'expert';
    const KEY_INVITE = 'invite';
    const FLASH_INVITE_SENDED = 'flash.invite_sended';

    /**
     * @Route("/logs/product/{slug}", name="logs_product")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function productAction(Request $request, Product $product)
    {
        $event = new ProductVisitEvent($product, $request);
        $this->get('event_dispatcher')->dispatch(ProductEvents::VISIT, $event);

        return new Response('', 200);
    }
}
