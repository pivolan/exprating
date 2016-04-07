<?php

/**
 * Date: 01.02.16
 * Time: 11:24.
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('id', 'mainSiteMenu')
            ->setChildrenAttribute('class', 'sf-menu sf-js-enabled sf-arrows');
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        /** @var CategoryRepository $entityRepository */
        $entityRepository = $em->getRepository('AppBundle:Category');
        /** @var Category[] $categories */
        $categories = $entityRepository->getFirstLevel();
        $categoryNumber = 0;
        $otherKey = 'Прочие';
        foreach ($categories as $category) {
            if (!$category->getIsHidden()) {
                $categoryNumber++;
                if ($categoryNumber >= 7) {
                    if (!isset($otherMenu)) {
                        $otherMenu = $menu->addChild(
                            $otherKey,
                            [
                                'uri' => '#',
                            ]
                        );
                        $otherMenu->setLinkAttribute('class', 'sf-with-ul');
                    }
                }
                if (isset($otherMenu)) {
                    $categoryMenu = $otherMenu->addChild(
                        $category->getName(),
                        [
                            'route'           => 'product_list',
                            'routeParameters' => [
                                'slug' => $category->getSlug(),
                            ],
                        ]
                    );
                } else {
                    $categoryMenu = $menu->addChild(
                        $category->getName(),
                        [
                            'route'           => 'product_list',
                            'routeParameters' => [
                                'slug' => $category->getSlug(),
                            ],
                        ]
                    );
                }
                foreach ($category->getChildren() as $childCategory) {
                    if (!$childCategory->getIsHidden()) {
                        $categoryMenu->setLinkAttribute('class', 'sf-with-ul');
                        $categoryMenu->addChild(
                            $childCategory->getName(),
                            [
                                'route'           => 'product_list',
                                'routeParameters' => [
                                    'slug' => $childCategory->getSlug(),
                                ],
                            ]
                        );
                    }
                }
            }
        }

        return $menu;
    }

    public function productFilterMenu(FactoryInterface $factory)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('role', 'menu')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $serializer = $this->container->get('serializer');
        /** @var ProductFilter $productFilter */
        $productFilter = $serializer->denormalize($request->attributes->all(), ProductFilter::class, null);
        $slug = $productFilter->getCategory()->getSlug();
        $direction = $productFilter->getSortDirection();
        $status = $productFilter->getStatus();
        $sortField = $productFilter->getSortField();

        $menu->addChild(
            'по цене',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_MIN_PRICE,
                    'sortDirection' => $direction,
                    'status'        => $status,
                ],
            ]
        )->setLinkAttribute('rel', 'nofollow');

        $menu->addChild(
            'по дате',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_ENABLED_AT,
                    'sortDirection' => $direction,
                    'status'        => $status,
                ],
            ]
        )->setLinkAttribute('rel', 'nofollow');

        $menu->addChild(
            'по рейтингу',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_RATING,
                    'sortDirection' => $direction,
                    'status'        => $status,
                ],
            ]
        )->setLinkAttribute('rel', 'nofollow');
        $menu->addChild('divider', ['divider' => true])->setAttribute('class', 'divider');
        $menu->addChild(
            'по возрастанию',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => $sortField,
                    'sortDirection' => ProductFilter::DIRECTION_ASC,
                    'status'        => $status,
                ],
            ]
        )->setLinkAttribute('rel', 'nofollow');

        $menu->addChild(
            'по убыванию',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => $sortField,
                    'sortDirection' => ProductFilter::DIRECTION_DESC,
                    'status'        => $status,
                ],
            ]
        )->setLinkAttribute('rel', 'nofollow');

        return $menu;
    }
}
