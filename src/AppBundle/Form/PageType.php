<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', ['label' => 'oktothek.page_title_label'])
            ->add('text', 'textarea', ['label' => 'oktothek.page_text_label', 'attr' => ['rows' => 35]])
            ->add('tags', 'tag')
            ->add('isActive', 'checkbox', ['label' => 'oktothek.page_isActive_label'])
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_page';
    }
}
