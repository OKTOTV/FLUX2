<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CoursetypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'oktothek.backend_coursetype_title_label'])
            ->add('description', TextareaType::class, ['label' => 'oktothek.backend_coursetype_description_label'])
            ->add('price', MoneyType::class, ['label' => 'oktothek.backend_coursetype_price_label'])
            ->add('price_reduced', MoneyType::class, ['label' => 'oktothek.backend_coursetype_priceReduced_label'])
            ->add('highlight',CheckboxType::class, ['label' => 'oktothek.backend_coursetype_highlight_label'])
            ->add('is_active', CheckboxType::class, ['label' => 'oktothek.backend_coursetype_isActive_label'])
            ->add('image', 'asset', ['label' => 'oktothek.backend_coursetype_image_label'])
            ->add('assets', 'assets', ['label' => 'oktothek.backend_coursetype_assets_label'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course\Coursetype'
        ));
    }

    public function getName()
    {
        return 'appbundle_coursetype';
    }
}
