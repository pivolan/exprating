<?php
/**
 * Date: 01.02.16
 * Time: 11:24
 */
namespace AppBundle\Menu;

use AppBundle\Entity\Category;
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
}