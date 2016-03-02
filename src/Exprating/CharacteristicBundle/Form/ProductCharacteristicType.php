<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace Exprating\CharacteristicBundle\Form;

use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCharacteristicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductCharacteristic::class,
            ]
        );
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var ProductCharacteristic $productCharacteristic */
        $productCharacteristic = $event->getData();
        if (!$productCharacteristic) {
            $form->add('value', null, ['label' => 'Значение']);
            $form->add('characteristic', null, ['label' => 'Название характеристики']);

            return;
        }
        $form->add('characteristic', null, ['label' => 'Название характеристики', 'disabled' => true]);
        $characteristic = $productCharacteristic->getCharacteristic();
        switch ($characteristic->getType()) {
            case Characteristic::TYPE_DECIMAL:
                $form->add(
                    'value',
                    NumberType::class,
                    [
                        'label' => $characteristic->getLabel().', '.
                                   $characteristic->getScale(),
                        'scale' => 2,
                    ]
                );
                break;
            case Characteristic::TYPE_INT:
                $form->add(
                    'value',
                    NumberType::class,
                    [
                        'label' => $characteristic->getLabel().', '.
                                   $characteristic->getScale(),
                        'scale' => 0,
                    ]
                );
                break;
            case Characteristic::TYPE_STRING:
                $form->add('value', TextType::class, ['label' => $characteristic->getLabel()]);
                break;
            default:
                throw new CharacteristicTypeException();
        }
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var ProductCharacteristic $data */
        $data = $event->getData();
        $form = $event->getForm();
        if (($data->getProduct() == null) && $form->getParent() && $form->getParent()->getParent()) {
            $product = $form->getParent()->getParent()->getData();
            $data->setProduct($product);
            $data->setValue($data->getValueString());
            $event->setData($data);
        }
    }
}
