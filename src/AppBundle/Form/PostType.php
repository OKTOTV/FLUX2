<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Okto\MediaBundle\Form\TagType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Bprs\AssetBundle\Form\Type\AssetType;
use Bprs\AssetBundle\Form\Type\AssetCollectionType;


class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'oktothek.post_name_label'
            ])
            ->add('teaser', TextareaType::class, ['label' => 'oktothek.post_teaser_label'])
            ->add('description', TextareaType::class, ['label' => 'oktothek.post_description_label', 'attr' => ['rows' => 32]])
            ->add('isActive', CheckboxType::class, ['label' => 'oktothek.post_isActive_label'])
            ->add('pinned', CheckboxType::class, ['label' => 'oktothek.post_pinned_label'])
            ->add('onlineAt', DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'html5' => false,
                    //'format' => 'd.m.Y H:i',
                    'label' => 'oktothek.post_onlineAt_label',
                    //'attr' => ['placeholder' => 'oktothek.post_onlineAt_placeholder']
                ])
            ->add('assets', AssetCollectionType::class, ['label' => 'oktothek.post_assets_label'])
            ->add('tags', TagType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Post']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }
}
