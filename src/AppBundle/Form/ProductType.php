<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Form\ImageType;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Form\ProductCharacteristicType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    const PUBLISH_SUBMIT = 'publish';
    const SAVE_SUBMIT = 'save';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, ['disabled' => true])
            ->add('rating1', TextType::class)
            ->add('rating2', TextType::class)
            ->add('rating3', TextType::class)
            ->add('rating4', TextType::class)
            ->add(
                'advantages',
                CollectionType::class,
                [
                    'label'         => 'Достоинства',
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'entry_options' => ['label' => false],
                ]
            )
            ->add(
                'disadvantages',
                CollectionType::class,
                [
                    'label'         => 'Недостатки',
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'entry_options' => ['label' => false],
                ]
            )
            ->add('expertOpinion', null, ['label' => 'Заключение'])
            ->add('expertComment', TextareaType::class, ['label' => 'Комментарий'])
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type'    => ImageType::class,
                    'entry_options' => ['label' => false],
                    'allow_delete'  => true,
                    'allow_add'     => true,
                ]
            )
            ->add(
                'productCharacteristics',
                CollectionType::class,
                [
                    'entry_type'    => ProductCharacteristicType::class,
                    'entry_options' => [
                        'label'         => false,
                        'error_mapping' => ['.' => 'value'],
                    ],
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'by_reference'  => false,
                    'label'         => 'Характеристики',
                ]
            )
            ->add(self::SAVE_SUBMIT, SubmitType::class, ['label' => 'Сохранить'])
            ->add(self::PUBLISH_SUBMIT, SubmitType::class, ['label' => 'Опубликовать'])
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
        /** @var Form|Form[] $form */
        $form = $event->getForm();
        /** @var Product $product */
        $product = $event->getData();
        if (!$product) {
            return;
        }
        $category = $product->getCategory();
        if (!$category) {
            return;
        }
        $form
            ->add('rating1', TextType::class, ['label' => $category->getRatingSettings()->getRating1Label()])
            ->add('rating2', TextType::class, ['label' => $category->getRatingSettings()->getRating2Label()])
            ->add('rating3', TextType::class, ['label' => $category->getRatingSettings()->getRating3Label()])
            ->add('rating4', TextType::class, ['label' => $category->getRatingSettings()->getRating4Label()]);

        $characteristics = [];
        foreach ($product->getProductCharacteristics() as $productCharacteristic) {
            $characteristics[] = $productCharacteristic->getCharacteristic();
        }
        foreach ($category->getCategoryCharacteristics() as $categoryCharacteristic) {
            if (!in_array($categoryCharacteristic->getCharacteristic(), $characteristics)) {
                $productCharacteristic = new ProductCharacteristic();
                $productCharacteristic->setProduct($product)
                    ->setCharacteristic($categoryCharacteristic->getCharacteristic())
                    ->setOrderIndex(count($characteristics) + $categoryCharacteristic->getOrderIndex());
                $product->addProductCharacteristic($productCharacteristic);
            }
        }
    }
}
