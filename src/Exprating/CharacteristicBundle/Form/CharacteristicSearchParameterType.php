<?php

/**
 * Date: 09.02.16
 * Time: 14:51.
 */

namespace Exprating\CharacteristicBundle\Form;

use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class CharacteristicSearchParameterType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\HiddenType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CharacteristicSearchParameter::class,
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        /** @var CharacteristicSearchParameter $characteristicSearchParameter */
        $characteristicSearchParameter = $event->getData();

        /** @var Characteristic $characteristic */
        $characteristic = $this->em->getRepository('CharacteristicBundle:Characteristic')
            ->find($characteristicSearchParameter->getName());

        $form = $event->getForm();
        if ($characteristic->getType() == Characteristic::TYPE_STRING) {
            $form->add('value', null, ['label' => $characteristic->getLabel()]);
        } else {
            $form->add('valueGTE', null, ['label' => $characteristic->getLabel().' от'])
                ->add('valueLTE', null, ['label' => $characteristic->getLabel().' до']);
        }
    }
}
