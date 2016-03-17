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
        $userId = $request->get('id');
        if ($userId == '#') {
            $user = $this->getUser();
        } else {
            $user = $this->getEm()->getRepository('AppBundle:User')->find($userId);
        }
        if (!$user) {
            $this->createNotFoundException();
        }

        $liipCacheManager = $this->get('liip_imagine.cache.manager');
        $treeIconFilter = 'tree_icon_filter';
        $userIcon = $liipCacheManager->getBrowserPath(
            $user->getAvatarImage() ?: '/images/default_user.png',
            $treeIconFilter
        );

        $result = [
            'id'       => $user->getId(),
            'text'     => $user->getUsername(),
            'state'    => ['opened' => true],
            'icon'     => $userIcon,
            'children' => [
                [
                    'id'       => $user->getId().'pages',
                    'text'     => 'Страницы',
                    'state'    => ['opened' => true],
                    'icon'     => 'glyphicon glyphicon-book',
                    'children' => [
                    ],
                ],
            ],
        ];

        foreach ($user->getProducts() as $product) {
            $result['children'][0]['children'][] = [
                'id'     => $product->getSlug(),
                'text'   => $product->getName(),
                'icon'   => $liipCacheManager->getBrowserPath($product->getPreviewImage(), $treeIconFilter),
                'a_attr' => [
                    'data-href' => $this->generateUrl('product_edit', ['slug' => $product->getSlug()]),
                    'target'    => '_blank',
                ],
            ];
        }

        foreach ($user->getExperts() as $expert) {
            $expertText = sprintf(
                '%s (страниц: %d, экспертов: %d, доходных страниц: %d, доходных экспертов: %d)',
                $expert->getFullName() ?: $expert->getUsername(),
                $expert->getProducts()->count(),
                $expert->getExperts()->count(),
                0,
                0
            );
            $result['children'][] = [
                'id'       => $expert->getId(),
                'text'     => $expertText,
                'icon'     => $liipCacheManager->getBrowserPath(
                    $expert->getAvatarImage() ?: '/images/default_user.png',
                    $treeIconFilter
                ),
                'a_attr'   => [
                    'data-href' => $this->generateUrl('experts_detail', ['username' => $expert->getUsername()]),
                    'target'    => '_blank',
                ],
                'children' => true,
            ];
        }

        return new JsonResponse($result);
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
