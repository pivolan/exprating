<?php

namespace Exprating\CharacteristicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package Exprating\CharacteristicBundle\Controller
 *
 * @Route("/characteristic/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('CharacteristicBundle:Default:index.html.twig');
    }
}
