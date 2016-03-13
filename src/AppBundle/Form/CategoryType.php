<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('ratingSettings', RatingSettingsType::class, ['label' => 'Настройка рейтингов'])
            ->add('seo', SeoType::class, ['label' => 'Настройки СЕО'])
            ->add('peopleGroups', null, ['label' => 'Группа людей', 'multiple' => true, 'expanded' => true])
            ->add(
                'characteristics',
                Select2EntityType::class,
                [
                    'multiple'             => true,
                    'remote_route'         => 'category_admin_category_ajax',
                    'class'                => Characteristic::class,
                    'text_property'        => 'label',
                    'page_limit'           => null,
                    'primary_key'          => 'slug',
                    'label'                => 'Характеристики',
                    'minimum_input_length' => 0,
                ]
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
