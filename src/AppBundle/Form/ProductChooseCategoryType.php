<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Glifery\EntityHiddenTypeBundle\Form\Type\EntityHiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductChooseCategoryType extends AbstractType
{
    const PUBLISH_SUBMIT = 'publish';
    const SAVE_SUBMIT = 'save';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityHiddenType::class, ['class' => Category::class, 'property' => 'slug'])
            ->add('peopleGroups', null, ['expanded' => true])
            ->add('save', SubmitType::class, ['label' => 'Сохранить']);
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
}
