<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;


class ImportController extends BaseController
{
    /**
     * @Route("/importPictures")
     */
    /**
     * @Route("/import/{tovar}", name="import_partner_product")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function importPicturesAction(Request $request, $tovar)
    {
        $test = $request;

        return new JsonResponse($test);

        return $this->render('AppBundle:Import:import_pictures.html.twig', array(
            // ...
        ));
    }

}
