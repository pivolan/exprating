<?php
/**
 * Date: 11.02.16
 * Time: 1:42
 */

namespace Exprating\ExpertBundle\Form;


use AppBundle\Entity\Product;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Exprating\CharacteristicBundle\Tests\Entity\ProductCharacteristicTest;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductCharacteristic::class
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var ProductCharacteristic $productCharacteristic */
        $productCharacteristic = $event->getData();
        if (!$productCharacteristic) {
            $form->add('value', null, ['label'=>'Значение']);
            $form->add('characteristic', null, ['label'=>'Название характеристики']);
            return;
        }
        $characteristic = $productCharacteristic->getCharacteristic();
        switch ($characteristic->getType()) {
            case Characteristic::TYPE_DECIMAL:
                $form->add('valueDecimal', NumberType::class, ['label' => $characteristic->getLabel() . ', ' .
                                                                          $characteristic->getScale(),
                                                               'scale' => 2]);
                break;
            case Characteristic::TYPE_INT:
                $form->add('valueInt', null, ['label' => $characteristic->getLabel() . ', ' .
                                                         $characteristic->getScale()]);
                break;
            case Characteristic::TYPE_STRING:
                $form->add('valueString', null, ['label' => $characteristic->getLabel()]);
                break;
            default:
                throw new CharacteristicTypeException();
        }
    }
}