<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeratorCommentType extends AbstractType
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
            ->add(self::APPROVE, SubmitType::class, ['label' => 'Одобрить'])
            ->add(self::REJECT, SubmitType::class, ['label' => 'Отклонить'])
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Comment::class,
            ]
        );
    }

    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var Comment $comment */
        $comment = $event->getData();
        if ($form->get(self::APPROVE)->isClicked()) {
            $comment->setIsPublished(true);
        } elseif ($form->get(self::REJECT)->isClicked()) {
            $comment->setIsPublished(false);
        }
    }
}
