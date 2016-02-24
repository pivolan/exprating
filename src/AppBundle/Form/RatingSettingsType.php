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
            ->add('rating1label', null, ['label'=>''])
            ->add('rating1weight', null, ['label'=>''])
            ->add('rating2label', null, ['label'=>''])
            ->add('rating2weight', null, ['label'=>''])
            ->add('rating3label', null, ['label'=>''])
            ->add('rating3weight', null, ['label'=>''])
            ->add('rating4label', null, ['label'=>''])
            ->add('rating4weight', null, ['label'=>''])
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
