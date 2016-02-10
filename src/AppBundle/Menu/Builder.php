<?php
/**
 * Date: 01.02.16
 * Time: 11:24
 */
namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use AppBundle\SortProduct\SortProduct;
use Doctrine\ORM\EntityManager;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('id', 'mainSiteMenu')
            ->setChildrenAttribute('class', 'sf-menu sf-js-enabled sf-arrows');
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        /** @var NestedTreeRepository $entityRepository */
        $entityRepository = $em->getRepository('AppBundle:Category');
        /** @var Category[] $categories */
        $categories = $entityRepository->getRootNodes();
        foreach ($categories as $category) {
            $menu->addChild($category->getName(), ['route'           => 'product_list',
                                                   'routeParameters' => ['slug' => $category->getSlug()]]);
            foreach ($category->getChildren() as $childCategory) {
                $menu[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                $menu[$category->getName()]->addChild(
                    $childCategory->getName(),
                    ['route'           => 'product_list',
                     'routeParameters' => ['slug' => $childCategory->getSlug()]]);
            }
        }

        return $menu;
    }

    public function sortProductMenu(FactoryInterface $factory, array $options)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('role', 'menu')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $slug = $request->get('slug');
        $page = $request->get('page');
        $direction = $request->get('sortDirection');
        $field = $request->get('sortField');

        $menu->addChild('по цене', ['route'           => 'product_list',
                                    'routeParameters' => ['slug'          => $slug,
                                                          'page'          => $page,
                                                          'sortField'     => SortProduct::FIELD_MIN_PRICE,
                                                          'sortDirection' => $direction
                                    ]]);

        $menu->addChild('по дате', ['route'           => 'product_list',
                                    'routeParameters' => ['slug'          => $slug,
                                                          'page'          => $page,
                                                          'sortField'     => SortProduct::FIELD_ENABLED_AT,
                                                          'sortDirection' => $direction
                                    ]]);

        $menu->addChild('по рейтингу', ['route'           => 'product_list',
                                        'routeParameters' => ['slug'          => $slug,
                                                              'page'          => $page,
                                                              'sortField'     => SortProduct::FIELD_RATING,
                                                              'sortDirection' => $direction
                                        ]]);
        $menu->addChild('divider', ['divider' => true])->setAttribute('class', 'divider');
        $menu->addChild('по возрастанию', ['route'           => 'product_list',
                                           'routeParameters' => ['slug'          => $slug,
                                                                 'page'          => $page,
                                                                 'sortField'     => $field,
                                                                 'sortDirection' => SortProduct::DIRECTION_ASC
                                           ]]);

        $menu->addChild('по убыванию', ['route'           => 'product_list',
                                        'routeParameters' => ['slug'          => $slug,
                                                              'page'          => $page,
                                                              'sortField'     => $field,
                                                              'sortDirection' => SortProduct::DIRECTION_DESC
                                        ]]);
        return $menu;
    }


}