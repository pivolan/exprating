<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', null, ['label'=>'Роли'])
            ->add('categories', null, ['label'=>'Отзыв'])
            ->add('adminCategories', null, ['label'=>'Отзыв'])
            ->add('curator', null, ['label'=>'Отзыв'])
            ->add('experts', null, ['label'=>'Отзыв'])
            ->add('save', SubmitType::class, ['label'=>'Сохранить'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
