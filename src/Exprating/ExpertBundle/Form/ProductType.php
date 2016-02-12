<?php
/**
 * Date: 11.02.16
 * Time: 1:42
 */

namespace Exprating\ExpertBundle\Form;


use AppBundle\Entity\Product;
use Doctrine\DBAL\Types\ArrayType;
use Exprating\CharacteristicBundle\Tests\Entity\ProductCharacteristicTest;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating1', null)
            ->add('rating2', null)
            ->add('rating3', null)
            ->add('rating4', null)
            ->add('advantages', CollectionType::class, ['label'        => 'Достоинства',
                                                        'allow_add'    => true,
                                                        'allow_delete' => true])
            ->add('disadvantages', CollectionType::class, ['label'        => 'Недостатки',
                                                           'allow_add'    => true,
                                                           'allow_delete' => true])
            ->add('expertOpinion', CKEditorType::class, ['label' => 'Заключение'])
            ->add('expertComment', null, ['label' => 'Комментарий'])
            ->add('productCharacteristics', CollectionType::class, ['entry_type'    => ProductCharacteristicType::class,
                                                                    'entry_options' => ['label' => false],
                                                                    'label'         => 'Характеристики'])
            ->add('Сохранить', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }

    public function onPreSetData(FormEvent $event)
    {
        /** @var Form|Form[] $form */
        $form = $event->getForm();
        /** @var Product $product */
        $product = $event->getData();
        $category = $product->getCategory();
        $form
            ->add('rating1', null, ['label' => $category->getRatingSettings()->getRating1Label()])
            ->add('rating2', null, ['label' => $category->getRatingSettings()->getRating2Label()])
            ->add('rating3', null, ['label' => $category->getRatingSettings()->getRating3Label()])
            ->add('rating4', null, ['label' => $category->getRatingSettings()->getRating4Label()]);
    }
}