<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace Exprating\CharacteristicBundle\Form;

use AppBundle\Entity\Category;
use Exprating\CharacteristicBundle\Entity\CategoryCharacteristic;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CategoryCharacteristicType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'characteristic',
                Select2EntityType::class,
                [
                    'multiple'             => false,
                    'remote_route'         => 'ajax_characteristics',
                    'class'                => Characteristic::class,
                    'text_property'        => 'name',
                    'page_limit'           => null,
                    'primary_key'          => 'slug',
                    'label'                => null,
                    'minimum_input_length' => 0,
                    'required'             => true,
                ]
            )
            ->add('headGroup', null, ['required'=>false])
            ->add('orderIndex', HiddenType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CategoryCharacteristic::class,
            ]
        );
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var CategoryCharacteristic $data */
        $data = $event->getData();
        $form = $event->getForm();
        if (($data->getCategory() == null) && $form->getParent() && $form->getParent()->getParent()) {
            $category = $form->getParent()->getParent()->getData();
            $data->setCategory($category);
        }
    }
}
