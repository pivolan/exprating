<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\CreateExpertRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateExpertRequestApproveType extends AbstractType
{
    const APPROVE = 'approve';
    const REJECT = 'reject';

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::APPROVE, SubmitType::class, ['label' => 'Одобрить'])
            ->add(self::REJECT, SubmitType::class, ['label' => 'Отклонить']);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateExpertRequest::class,
            ]
        );
    }
}
