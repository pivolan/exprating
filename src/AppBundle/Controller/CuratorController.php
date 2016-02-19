<?php
/**
 * Date: 20.02.16
 * Time: 1:36
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CuratorController
 * @package AppBundle\Controller
 * @Security("is_granted('ROLE_EXPERT_CURATOR')")
 */
class CuratorController extends BaseController
{
    /**
     * @Route("/wait_list/{page}", name="curator_wait_list", defaults={"page":1})
     */
    public function waitList(Request $request, $page)
    {
        $query = $this->getEm()->getRepository('AppBundle:CuratorDecision')->waitByCurator($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            max($page, 1),
            self::LIMIT_PER_PAGE
        );
        $template = 'Curator/wait_list.html.twig';
        if ($request->isXmlHttpRequest()) {
            $template = 'Curator/wait_listPart.html.twig';
        }
        return $this->render($template, [self::KEY_PAGINATION => $pagination]);
    }
}