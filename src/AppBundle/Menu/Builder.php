<?php

/**
 * Date: 01.02.16
 * Time: 11:24.
 */

namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
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
        $peopleGroups = [
            PeopleGroup::SLUG_WOMAN => 'Для женщин',
            PeopleGroup::SLUG_MAN   => 'Для мужчин',
            PeopleGroup::SLUG_CHILD => 'Для детей',
            PeopleGroup::SLUG_ALL   => '',
        ];
        foreach ($peopleGroups as $peopleGroup => $label) {
            if (empty($label)) {
                $menuChild = $menu;
            } else {
                $menuChild = $menu->addChild($peopleGroup, ['uri' => '#', 'label' => $label])->setLinkAttribute(
                    'class',
                    'sf-with-ul'
                );
            }
            foreach ($categories as $category) {
                if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, $peopleGroup))) {
                    $menuChild->addChild(
                        $category->getName(),
                        [
                            'route'           => 'product_list',
                            'routeParameters' => [
                                'slug'        => $category->getSlug(),
                                'peopleGroup' => $peopleGroup,
                            ],
                        ]
                    );
                    foreach ($category->getChildren() as $childCategory) {
                        if ($category->getPeopleGroups()->contains(
                            $em->getReference(PeopleGroup::class, $peopleGroup)
                        )
                        ) {
                            $menuChild[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                            $menuChild[$category->getName()]->addChild(
                                $childCategory->getName(),
                                [
                                    'route'           => 'product_list',
                                    'routeParameters' => [
                                        'slug'        => $childCategory->getSlug(),
                                        'peopleGroup' => $peopleGroup,
                                    ],
                                ]
                            );
                        }
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
        $peopleGroup = $productFilter->getPeopleGroup()->getSlug();

        $menu->addChild(
            'по цене',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_MIN_PRICE,
                    'sortDirection' => $direction,
                    'status'        => $status,
                    'peopleGroup'   => $peopleGroup,
                ],
            ]
        );

        $menu->addChild(
            'по дате',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_ENABLED_AT,
                    'sortDirection' => $direction,
                    'status'        => $status,
                    'peopleGroup'   => $peopleGroup,
                ],
            ]
        );

        $menu->addChild(
            'по рейтингу',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => ProductFilter::FIELD_RATING,
                    'sortDirection' => $direction,
                    'status'        => $status,
                    'peopleGroup'   => $peopleGroup,
                ],
            ]
        );
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
                    'peopleGroup'   => $peopleGroup,
                ],
            ]
        );

        $menu->addChild(
            'по убыванию',
            [
                'route'           => 'product_list',
                'routeParameters' => [
                    'slug'          => $slug,
                    'sortField'     => $sortField,
                    'sortDirection' => ProductFilter::DIRECTION_DESC,
                    'status'        => $status,
                    'peopleGroup'   => $peopleGroup,
                ],
            ]
        );

        return $menu;
    }
}
