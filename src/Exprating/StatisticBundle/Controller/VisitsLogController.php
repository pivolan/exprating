<?php

namespace Exprating\StatisticBundle\Controller;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Product;
use Exprating\StatisticBundle\Entity\Visit;
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
        $expert = $product->getExpertUser();
        $curatorFirstLevel = null;
        if ($expert && $product->getIsEnabled()) {
            $curatorFirstLevel = $expert->getCurator();
            $visit->setProduct($product)
                ->setUser($this->getUser())
                ->setExpert($expert)
                ->setCuratorFirstLevel($curatorFirstLevel)
                ->setIp($request->getClientIp())
                ->setUserAgent($request->headers->get('User-Agent'))
                ->setUrl($request->getUri());
            if ($expert->getCurator()) {
                $visit->setCuratorSecondLevel($expert->getCurator()->getCurator());
            }
            $product->setVisitsCount($product->getVisitsCount() + 1);
            $this->getEm()->persist($visit);
            $this->getEm()->flush();
        }

        return new Response();
    }
}
