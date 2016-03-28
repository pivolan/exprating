<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use Exprating\CharacteristicBundle\Entity\CategoryCharacteristic;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Form\CategoryCharacteristicsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CategoryType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('peopleGroups', null, ['label' => 'Группа людей', 'multiple' => true, 'expanded' => true])
            ->add('seo', SeoType::class, ['label' => 'Настройки СЕО'])
            ->add('ratingSettings', RatingSettingsType::class, ['label' => 'Настройка рейтингов'])
            ->add(
                'categoryCharacteristics',
                CategoryCharacteristicsType::class,
                ['label'=> null]
            )
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Category::class,
            ]
        );
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var Category $category */
        $category = $event->getData();
        $category->getSeo()->setCategory($category);
    }
}
