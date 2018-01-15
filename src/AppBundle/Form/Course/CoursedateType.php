<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CoursedateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('courseStart', DateTimeType::class,
            [
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'oktothek.backend_coursedate_start_label'
            ])
            ->add('courseEnd', DateTimeType::class,
            [
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'oktothek.backend_coursedate_end_label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Course\Coursedate',
            'error_bubbling' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_coursedate';
    }
}
