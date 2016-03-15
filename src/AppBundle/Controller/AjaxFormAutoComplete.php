<?php
/**
 * Date: 14.03.16
 * Time: 3:03
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AjaxFormAutoComplete extends BaseController
{
    /**
     * @Route("/ajax_form/characteristics", name="ajax_characteristics")
     */
    public function characteristicsAjaxAction(Request $request)
    {
        $pageLimit = $request->get('page_limit');
        $q = $request->get('q');
        $skip = 0;
        $characteristics = $this->getEm()->getRepository('CharacteristicBundle:Characteristic')->getIdNameByQ(
            $q,
            $pageLimit,
            $skip
        );

        return new JsonResponse($characteristics);
    }

    /**
     * @Route("/ajax_form/categories", name="ajax_categories")
     */
    public function categoriesAjaxAction(Request $request)
    {
        $pageLimit = $request->get('page_limit');
        $q = $request->get('q');
        $skip = 0;
        $characteristics = $this->getEm()->getRepository('AppBundle:Category')->getIdNameByQ(
            $q,
            $pageLimit,
            $skip
        );

        return new JsonResponse($characteristics);
    }

    /**
     * @Route("/ajax/expert/tree", name="ajax_expert_tree")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function expertAjaxTree(Request $request)
    {
        return new JsonResponse(
            [
                'id'       => $this->getUser()->getId(),
                'text'     => $this->getUser()->getUsername(),
                'children' => [
                    [
                        'id'       => 'experts',
                        'text'     => 'Эксперты',
                        'children' => [
                            ['id' => 'curator', 'text' => 'Куратор 1', 'children' => true],
                        ],
                    ],
                    [
                        'id'       => 'pages',
                        'text'     => 'Страницы',
                        'children' => [
                            ['id'=>'slug', 'text'=>'Страница1'],
                        ],
                    ],
                ],
            ]
        );
    }
}
