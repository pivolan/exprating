<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
            ->add('categories', null, ['label' => 'Доступные категории'])
            ->add('adminCategories', null, ['label' => 'Категории админа'])
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
