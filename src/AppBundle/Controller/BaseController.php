<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    const KEY_PRODUCTS = 'products';
    const KEY_FORM_SEARCH = 'formSearch';

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
}
