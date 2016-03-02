<?php

/**
 * Date: 20.02.16
 * Time: 13:14.
 */

namespace AppBundle\Form;

use AppBundle\Entity\CuratorDecision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    const APPROVE = 'approve';
    const REJECT = 'reject';

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rejectReason', TextareaType::class, ['label' => 'Укажите причину отказа', 'required' => false])
            ->add(self::APPROVE, SubmitType::class, ['label' => 'Одобрить'])
            ->add(self::REJECT, SubmitType::class, ['label' => 'Отправить'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CuratorDecision::class,
            ]
        );
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        if ($form->get(self::REJECT)->isClicked()) {
            $form->add('rejectReason', null, ['required' => true]);
        }
    }

    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var CuratorDecision $decision */
        $decision = $event->getData();
        if ($form->get(self::APPROVE)->isClicked()) {
            $decision->setStatus(CuratorDecision::STATUS_APPROVE);
        } elseif ($form->get(self::REJECT)->isClicked()) {
            $decision->setStatus(CuratorDecision::STATUS_REJECT);
        }
    }
}
