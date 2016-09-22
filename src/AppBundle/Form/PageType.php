<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Okto\MediaBundle\Form\TagType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'oktothek.page_title_label'])
            ->add('text', TextareaType::class, ['label' => 'oktothek.page_text_label', 'attr' => ['rows' => 35]])
            ->add('tags', TagType::class)
            ->add('isActive', CheckboxType::class, ['label' => 'oktothek.page_isActive_label'])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Page'
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_page';
    }
}
