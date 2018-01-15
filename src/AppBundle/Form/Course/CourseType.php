<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Course\CoursedateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coursetype', EntityType::class,
                [
                    'label' => 'oktothek.backend_course_coursetype_label',
                    'class' => 'AppBundle:Course\CourseType',
                ])
            ->add('trainer', TextType::class, ['label' => 'oktothek.backend_course_trainer_label'])
            ->add('max_attendees', IntegerType::class, ['label' => 'oktothek.backend_course_trainer_maxAttendees_label'])
            ->add(
                'is_active',
                CheckboxType::class,
                [
                    'label' => 'oktothek.backend_course_isActive_label',
                    'required' => false
                ]
            )
            ->add('dates', CollectionType::class, [
                'entry_type' => CoursedateType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'oktothek.backend_course_dates_label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Course\Course',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_course';
    }
}
