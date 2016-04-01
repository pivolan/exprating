<?php
/**
 * Date: 14.03.16
 * Time: 3:03
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $categories = $this->getEm()->getRepository('AppBundle:Category')->getIdNameByQ(
            $q,
            $pageLimit,
            $skip
        );

        return new JsonResponse($categories);
    }

    /**
     * @Route("/ajax/categories/{slug}", name="ajax_category_tree", defaults={"slug": null})
     * @ParamConverter(name="category", class="AppBundle\Entity\Category", options={"mapping":{"slug":"slug"}})
     */
    public function categoriesAjaxTreeAction(Request $request, Category $category = null)
    {
        $route = $request->get('route_name');
        $isAdmin = $request->get('is_admin');
        $user = null;
        $admin = null;
        if ($isAdmin) {
            $admin = $this->getUser();
        } else {
            $user = $this->getUser();
        }
        $result = [];

        $categories = $this->getEm()->getRepository('AppBundle:Category')->getForJsTree($user, $admin);
        $categoriesIndexed = [];
        foreach ($categories as $categoryArray) {
            $categoriesIndexed[$categoryArray['id']] = $categoryArray;
        }

        foreach ($categoriesIndexed as $categoryArray) {
            $parent = $categoryArray['parent_id'] ?: '#';
            if (!isset($categoriesIndexed[$categoryArray['parent_id']])) {
                $parent = '#';
            }
            $result[] = [
                'id'     => $categoryArray['id'],
                'parent' => $parent,
                'text'   => $categoryArray['name'],
                'a_attr' => [
                    'href'      => $this->generateUrl($route, ['slug' => $categoryArray['id']]),
                    'data_slug' => $categoryArray['id'],
                ],
                'state'  => [
                    'selected' => ($categoryArray['id'] == $category),
                ],
            ];
        }

        return new JsonResponse($result);
    }
}
