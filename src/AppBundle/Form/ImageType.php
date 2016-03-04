<?php
/**
 * Date: 04.03.16
 * Time: 9:05
 */

namespace AppBundle\Form;


use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename', HiddenType::class)
            ->add('isMain', HiddenType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Image::class,
            ]
        );
    }

    public function onPostSubmit(FormEvent $event)
    {
        /** @var Image $data */
        $data = $event->getData();
        $form = $event->getForm();
        if ($form->getParent() && $form->getParent()->getParent()) {
            /** @var Product $product */
            $product = $form->getParent()->getParent()->getData();
            if ($product instanceof Product) {
                $data->setProduct($product);
                if ($data->getIsMain()) {
                    $product->setPreviewImage($data->getFilename());
                }
            }
            $event->setData($data);
        }
    }
}
