<?php

/**
 * Date: 11.02.16
 * Time: 1:42.
 */

namespace Exprating\CharacteristicBundle\Form;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\Entity\CategoryCharacteristic;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Exprating\CharacteristicBundle\Form\DataTransformer\EntitiesToPropertyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

class CategoryCharacteristicsType extends CollectionType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['entry_type'] = CategoryCharacteristicType::class;
        parent::buildForm($builder, $options);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['headGroups'] = ['Основные характеристики' => []];
        foreach ($view->vars['form'] as $formView) {
            /** @var FormView $formView */
            $key = $formView->children['headGroup']->vars['value'];
            $view->vars['headGroups'][$key][] = $formView;
        }
        parent::finishView($view, $form, $options);
    }

    public function getBlockPrefix()
    {
        return 'category_characteristics';
    }
}
