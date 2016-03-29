<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Cocur\Slugify\Slugify;
use Glifery\EntityHiddenTypeBundle\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class CategoryCreateType extends AbstractType
{
    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * CategoryCreateType constructor.
     *
     * @param Slugify $slugify
     */
    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityHiddenType::class, ['class' => Category::class, 'property' => 'slug'])
            ->add('slug', null, ['label' => 'Уникальное название на латинице, будет отображаться в адресе'])
            ->add('name', null, ['label' => 'Название'])
            ->add('seo', SeoType::class, ['label' => 'Настройки СЕО'])
            ->add('ratingSettings', RatingSettingsType::class, ['label' => 'Настройка рейтингов'])
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
        $category->getRatingSettings()->setCategory($category);
    }
}
