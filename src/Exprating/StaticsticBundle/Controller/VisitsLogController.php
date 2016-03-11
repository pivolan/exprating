<?php

namespace Exprating\StaticsticBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Product;
use Exprating\StaticsticBundle\Entity\Visit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitsLogController extends BaseController
{
    /**
     * @Route("/log/visit/product/{slug}", name="log_visit_product")
     * @ParamConverter(name="product", class="AppBundle\Entity\Product", options={"mapping":{"slug":"slug"}})
     */
    public function productAction(Request $request, Product $product)
    {
        $visit = new Visit();
        $visit->setProduct($product)
            ->setUser($this->getUser())
            ->setExpert($product->getExpertUser())
            ->setCuratorFirstLevel($product->getExpertUser()->getCurator())
            ->setIp($request->getClientIp())
            ->setUserAgent($request->headers->get('User-Agent'))
            ->setUrl($request->getUri())
        ;
        if ($product->getExpertUser()->getCurator()) {
            $visit->setCuratorSecondLevel($product->getExpertUser()->getCurator()->getCurator());
        }
        $this->getEm()->persist($visit);
        $this->getEm()->flush();
        return new Response();
    }
}
