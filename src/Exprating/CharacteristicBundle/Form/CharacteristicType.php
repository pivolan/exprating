<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace Exprating\CharacteristicBundle\Form;

use AppBundle\Entity\Category;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacteristicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', null, ['label' => 'slug'])
            ->add('name', null, ['label' => 'Название уникальное'])
            ->add('group', null, ['label' => 'Группа'])
            ->add('type', null, ['label' => 'Тип'])
            ->add('label', null, ['label' => 'Название краткое'])
            ->add('scale', null, ['label' => 'Единицы иззмерения'])
            ->add('save', SubmitType::class, ['label' => 'Создать'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Characteristic::class,
            ]
        );
    }
}
