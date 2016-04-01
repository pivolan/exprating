<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\RegistrationRequest;
use Doctrine\ORM\EntityManager;
use Glifery\EntityHiddenTypeBundle\Form\Type\EntityHiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationRequestType extends AbstractType
{
    /** @var  EntityManager */
    protected $em;

    /**
     * RegistrationRequestType constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('message', TextareaType::class, ['label' => 'Сопроводительное письмо'])
            ->add(
                'categories',
                CollectionType::class,
                [
                    'entry_type'    => EntityHiddenType::class,
                    'entry_options' => ['property' => 'slug', 'class' => Category::class],
                    'allow_add'     => true,
                    'label'         => false,
                ]
            )
            ->add('send', SubmitType::class, ['label' => 'Отправить'])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => RegistrationRequest::class,
            ]
        );
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }
}
