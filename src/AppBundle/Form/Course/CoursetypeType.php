<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Bprs\AssetBundle\Form\Type\AssetType;
use Bprs\AssetBundle\Form\Type\AssetCollectionType;

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
            ->add('teaser', TextareaType::class, ['label' => 'oktothek.backend_coursetype_teaser_label', 'attr' => ['class' => 'character230']])
            ->add('description', TextareaType::class, ['label' => 'oktothek.backend_coursetype_description_label', 'attr' => ['rows' => 32]])
            ->add('price', MoneyType::class, ['label' => 'oktothek.backend_coursetype_price_label'])
            ->add('price_reduced', MoneyType::class, ['label' => 'oktothek.backend_coursetype_priceReduced_label'])
            ->add('highlight',CheckboxType::class, ['label' => 'oktothek.backend_coursetype_highlight_label', 'required' => false])
            ->add('is_active', CheckboxType::class, ['label' => 'oktothek.backend_coursetype_isActive_label', 'required' => false])
            ->add('image', AssetType::class, ['label' => 'oktothek.backend_coursetype_image_label'])
            ->add('assets', AssetCollectionType::class, ['label' => 'oktothek.backend_coursetype_assets_label'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Course\Coursetype'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_coursetype';
    }
}
