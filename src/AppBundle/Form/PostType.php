<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['label' => 'oktothek.post_name_label'
            ])
            ->add('description', 'textarea', ['label' => 'oktothek.post_description_label'])
            ->add('isActive', 'checkbox', ['label' => 'oktothek.post_isActive_label'])
            ->add('onlineAt', 'datetime',
                [
                    'widget' => 'single_text',
                    'html5' => false,
                    //'format' => 'd.m.Y H:i',
                    'label' => 'oktothek.post_onlineAt_label',
                    //'attr' => ['placeholder' => 'oktothek.post_onlineAt_placeholder']
                ])
            ->add('assets', 'assets', ['label' => 'oktothek.post_assets_label'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_post';
    }
}
