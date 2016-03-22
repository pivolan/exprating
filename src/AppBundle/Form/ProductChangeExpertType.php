<?php

/**
 * Date: 23.02.16
 * Time: 2:08.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductChangeExpertType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expertUser', null, ['label' => 'Эксперт'])
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Product::class,
            ]
        );
    }

    public function onPreSetData(FormEvent $event)
    {
        /** @var Product $product */
        $product = $event->getData();
        $form = $event->getForm();
        if ($product && $product->getExpertUser() && $product->getExpertUser()->getCurator()) {
            $users = $product->getExpertUser()->getCurator()->getExperts();
            $form->add('expertUser', null, ['choices' => $users]);
        }
    }
}
