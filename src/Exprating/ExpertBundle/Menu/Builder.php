<?php

/**
 * Date: 01.02.16
 * Time: 11:24.
 */

namespace Exprating\ExpertBundle\Menu;

use AppBundle\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function expertMenu(FactoryInterface $factory, array $options)
    {
        /** @var User $user */
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        /** @var ItemInterface|ItemInterface[] $menu */
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('id', 'mainSiteMenu')
            ->setChildrenAttribute('class', 'sf-menu sf-js-enabled sf-arrows');
        $menu->addChild('Создать обзор', ['route' => 'expert_categories']);
        $menu->addChild('Мои обзоры', ['route' => 'expert_cabinet']);
        $menu->addChild('Не завершенные обзоры', ['route' => 'expert_unpublished_products']);
        if ($user->hasRole(User::ROLE_EXPERT_CATEGORY_ADMIN)) {
            $menu->addChild('Редактирование категорий', ['route' => 'expert_category_admin_list']);
        }
        if ($user->hasRole(User::ROLE_EXPERT_CURATOR)) {
            $menu->addChild('Обзоры на модерации', ['route' => 'expert_curator_product_list_wait']);
            $menu->addChild('Редактирование экспертов', ['route' => 'expert_curator_product_list']);
        }
        if ($user->hasRole(User::ROLE_ADMIN)) {
            $menu->addChild('Назначение прав', ['route' => 'expert_admin_user_list']);
        }

        return $menu;
    }
}
