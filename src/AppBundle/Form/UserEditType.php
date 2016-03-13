<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class UserEditType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label'    => 'Роли',
                    'multiple' => true,
                    'expanded' => true,
                    'choices'  => [
                        'Простой пользователь' => User::ROLE_USER,
                        'Эксперт'              => User::ROLE_EXPERT,
                        'Админ категорий'      => User::ROLE_EXPERT_CATEGORY_ADMIN,
                        'Куратор'              => User::ROLE_EXPERT_CURATOR,
                        'Модератор'            => User::ROLE_MODERATOR,
                        'Админ'                => User::ROLE_ADMIN,
                    ],
                ]
            )
            ->add('categories', Select2EntityType::class,
                [
                    'multiple'             => true,
                    'remote_route'         => 'ajax_categories',
                    'class'                => Category::class,
                    'text_property'        => 'name',
                    'page_limit'           => null,
                    'primary_key'          => 'slug',
                    'label'                => 'Доступные категории',
                    'minimum_input_length' => 0,
                ])
            ->add('adminCategories', Select2EntityType::class,
                [
                    'multiple'             => true,
                    'remote_route'         => 'ajax_categories',
                    'class'                => Category::class,
                    'text_property'        => 'name',
                    'page_limit'           => null,
                    'primary_key'          => 'slug',
                    'label'                => 'Категории админа',
                    'minimum_input_length' => 0,
                ])
            ->add('curator', null, ['label' => 'Куратор'])
            ->add('experts', null, ['label' => 'Эксперты'])
            ->add('save', SubmitType::class, ['label' => 'Сохранить']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
