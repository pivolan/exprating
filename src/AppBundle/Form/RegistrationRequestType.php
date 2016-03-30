<?php
/**
 * Date: 10.03.16
 * Time: 4:01
 */

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\RegistrationRequest;
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
                EntityType::class,
                ['multiple' => true, 'choices' => [], 'class' => Category::class, 'label' => 'Категории']
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
        $choices = $this->em->getRepository('AppBundle:Category')->findBy(['slug' => $data['categories']]);
        $form->add(
            'categories',
            EntityType::class,
            ['multiple' => true, 'choices' => $choices, 'class' => Category::class, 'label' => 'Категории']
        );
    }
}
