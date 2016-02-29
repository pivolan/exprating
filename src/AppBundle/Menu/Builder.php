<?php
/**
 * Date: 01.02.16
 * Time: 11:24
 */
namespace AppBundle\Menu;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Repository\CategoryRepository;
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
        /** @var CategoryRepository $entityRepository */
        $entityRepository = $em->getRepository('AppBundle:Category');
        /** @var Category[] $categories */
        $categories = $entityRepository->getFirstLevel();
        $woman = $menu->addChild('Для женщин', ['uri'=>'#'])->setLinkAttribute('class', 'sf-with-ul');
        $man = $menu->addChild('Для мужчин', ['uri'=>'#'])->setLinkAttribute('class', 'sf-with-ul');
        $child = $menu->addChild('Для детей', ['uri'=>'#'])->setLinkAttribute('class', 'sf-with-ul');
        foreach ($categories as $category) {
            if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_WOMAN))) {
                $woman->addChild($category->getName(), ['route'           => 'product_list',
                                                        'routeParameters' => ['slug' => $category->getSlug()]]);
            }
            if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_MAN))) {
                $man->addChild($category->getName(), ['route'           => 'product_list',
                                                      'routeParameters' => ['slug' => $category->getSlug()]]);
            }
            if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_CHILD))) {
                $child->addChild($category->getName(), ['route'           => 'product_list',
                                                        'routeParameters' => ['slug' => $category->getSlug()]]);
            }
            if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_ALL))) {
                $menu->addChild($category->getName(), ['route'           => 'product_list',
                                                       'routeParameters' => ['slug' => $category->getSlug()]]);
            }
            foreach ($category->getChildren() as $childCategory) {
                if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_WOMAN))) {
                    $woman[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                    $woman[$category->getName()]->addChild(
                        $childCategory->getName(),
                        ['route'           => 'product_list',
                         'routeParameters' => ['slug' => $childCategory->getSlug()]]);
                }
                if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_MAN))) {
                    $man[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                    $man[$category->getName()]->addChild(
                        $childCategory->getName(),
                        ['route'           => 'product_list',
                         'routeParameters' => ['slug' => $childCategory->getSlug()]]);
                }
                if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_CHILD))) {
                    $child[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                    $child[$category->getName()]->addChild(
                        $childCategory->getName(),
                        ['route'           => 'product_list',
                         'routeParameters' => ['slug' => $childCategory->getSlug()]]);
                }
                if ($category->getPeopleGroups()->contains($em->getReference(PeopleGroup::class, PeopleGroup::SLUG_ALL))) {
                    $menu[$category->getName()]->setLinkAttribute('class', 'sf-with-ul');
                    $menu[$category->getName()]->addChild(
                        $childCategory->getName(),
                        ['route'           => 'product_list',
                         'routeParameters' => ['slug' => $childCategory->getSlug()]]);
                }
            }
        }

        return $menu;
    }

    public function productFilterMenu(FactoryInterface $factory, array $options)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('role', 'menu')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $slug = $request->get('slug');
        $page = $request->get('page');
        $direction = $request->get('sortDirection');
        $status = $request->get('status');
        $field = $request->get('sortField');

        $menu->addChild('по цене', ['route'           => 'product_list',
                                    'routeParameters' => ['slug'          => $slug,
                                                          'page'          => $page,
                                                          'sortField'     => ProductFilter::FIELD_MIN_PRICE,
                                                          'sortDirection' => $direction,
                                                          'status'        => $status
                                    ]]);

        $menu->addChild('по дате', ['route'           => 'product_list',
                                    'routeParameters' => ['slug'          => $slug,
                                                          'page'          => $page,
                                                          'sortField'     => ProductFilter::FIELD_ENABLED_AT,
                                                          'sortDirection' => $direction,
                                                          'status'        => $status
                                    ]]);

        $menu->addChild('по рейтингу', ['route'           => 'product_list',
                                        'routeParameters' => ['slug'          => $slug,
                                                              'page'          => $page,
                                                              'sortField'     => ProductFilter::FIELD_RATING,
                                                              'sortDirection' => $direction,
                                                              'status'        => $status
                                        ]]);
        $menu->addChild('divider', ['divider' => true])->setAttribute('class', 'divider');
        $menu->addChild('по возрастанию', ['route'           => 'product_list',
                                           'routeParameters' => ['slug'          => $slug,
                                                                 'page'          => $page,
                                                                 'sortField'     => $field,
                                                                 'sortDirection' => ProductFilter::DIRECTION_ASC,
                                                                 'status'        => $status
                                           ]]);

        $menu->addChild('по убыванию', ['route'           => 'product_list',
                                        'routeParameters' => ['slug'          => $slug,
                                                              'page'          => $page,
                                                              'sortField'     => $field,
                                                              'sortDirection' => ProductFilter::DIRECTION_DESC,
                                                              'status'        => $status
                                        ]]);
        return $menu;
    }


}