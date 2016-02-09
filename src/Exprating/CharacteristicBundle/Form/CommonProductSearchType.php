<?php
/**
 * Date: 09.02.16
 * Time: 14:51
 */

namespace Exprating\CharacteristicBundle\Form;

use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class CommonProductSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Название товара'])
            ->add('priceGTE', Type\MoneyType::class, ['label' => 'Цена от', 'required' => false])
            ->add('priceLTE', Type\MoneyType::class, ['label' => 'Цена до', 'required' => false])
            ->add('characteristics', Type\CollectionType::class, ['entry_type'    => CharacteristicSearchParameterType::class,
                                                                  'label'         => false,
                                                                  'entry_options' => ['label' => false, 'required' => false]
            ])
            ->add('Поиск', Type\SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommonProductSearch::class
        ]);
    }
}