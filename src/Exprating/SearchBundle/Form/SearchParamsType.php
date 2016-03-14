<?php

namespace Exprating\SearchBundle\Form;

use Exprating\SearchBundle\SearchParams\SearchParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchParamsType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string')
            ->setMethod('get');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => SearchParams::class,
                'csrf_protection' => false,
            ]
        );
    }
}
