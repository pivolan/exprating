<?php
/**
 * Date: 01.02.16
 * Time: 11:24
 */
namespace Exprating\ExpertBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function expertMenu(FactoryInterface $factory, array $options)
    {
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('id', 'mainSiteMenu')
            ->setChildrenAttribute('class', 'sf-menu sf-js-enabled sf-arrows');
        $menu->addChild('Создать обзор', ['route' => 'expert_categories']);
        $menu->addChild('Мои обзоры', ['route' => 'homepage']);
        $menu->addChild('Не завершенные обзоры', ['route' => 'homepage']);
        return $menu;
    }
}