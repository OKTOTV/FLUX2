<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\Course\CoursedateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coursetype')
            ->add('trainer')
            ->add('max_attendees')
            ->add('dates', CollectionType::class, [
                'entry_type' => CoursedateType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course\Course'
        ));
    }

    public function getName()
    {
        return 'appbundle_course';
    }
}
