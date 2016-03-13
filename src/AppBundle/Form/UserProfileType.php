<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', null, ['label' => 'Полное имя'])
            ->add('username', null, ['label' => 'Логин'])
            ->add('avatarImage', HiddenType::class)
            ->add('birthday', BirthdayType::class, ['label' => 'Дата рождения'])
            ->add('city', null, ['label' => 'Город'])
            ->add('caption', null, ['label' => 'Краткая информация'])
            ->add('skype', null, ['label' => 'Skype'])
            ->add('phone', null, ['label' => 'Номер телефона'])
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
