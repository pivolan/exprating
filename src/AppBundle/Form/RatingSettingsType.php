<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comment;
use AppBundle\Entity\RatingSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingSettingsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating1label', null, ['label'=>'Описание 1-го параметра'])
            ->add('rating1weight', null, ['label'=>'Вес 1-го параметра'])
            ->add('rating2label', null, ['label'=>'Описание 2-го параметра'])
            ->add('rating2weight', null, ['label'=>'Вес 2-го параметра'])
            ->add('rating3label', null, ['label'=>'Описание 3-го параметра'])
            ->add('rating3weight', null, ['label'=>'Вес 3-го параметра'])
            ->add('rating4label', null, ['label'=>'Описание 4-го параметра'])
            ->add('rating4weight', null, ['label'=>'Вес 4-го параметра'])
            ->add('save', SubmitType::class, ['label'=>'Сохранить'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RatingSettings::class
        ]);
    }
}
