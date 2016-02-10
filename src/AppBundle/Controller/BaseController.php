<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 * @package AppBundle\Controller
 *
 * @method User getUser()
 */
abstract class BaseController extends Controller
{
    const KEY_PRODUCT = 'product';
    const KEY_PRODUCTS = 'products';
    const KEY_FORM_SEARCH = 'formSearch';
    const KEY_FORM = 'form';

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
}
