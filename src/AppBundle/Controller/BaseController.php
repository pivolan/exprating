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
    const LIMIT_PER_PAGE = 9;
    const KEY_PAGINATION = 'pagination';
    const KEY_CATEGORY = 'category';
    const KEY_EXPERTS = 'experts';
    const KEY_TREE_HTML = 'treeHtml';

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
