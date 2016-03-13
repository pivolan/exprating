<?php

namespace AppBundle\Form;

use AppBundle\Entity\RatingSettings;
use AppBundle\Entity\Seo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['label' => 'Название страницы'])
            ->add('h1', null, ['label' => 'Заголовок H1'])
            ->add('metaKeywords', null, ['label' => 'Ключевые слова'])
            ->add('metaDescription', null, ['label' => 'Описание страницы']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Seo::class,
            ]
        );
    }
}
