<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController.
 *
 * @method User getUser()
 */
abstract class BaseController extends Controller
{
    const KEY_PRODUCT = 'product';
    const KEY_PRODUCTS = 'products';
    const KEY_FORM_SEARCH = 'formSearch';
    const KEY_FORM = 'form';
    const LIMIT_PER_PAGE = 9;
    const KEY_PAGINATION = 'pagination';
    const KEY_CATEGORY = 'category';
    const KEY_CATEGORIES = 'categories';
    const KEY_EXPERTS = 'experts';
    const KEY_USER = 'user';
    const KEY_PAGE = 'page';

    const SUCCESS_RESPONSE_OK = 'ok';

    const FLASH_MESSAGE = 'flash.message';
    const FLASH_ERROR_MESSAGE = 'flash.error_message';

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->getDoctrine()->getManager();
    }
}
