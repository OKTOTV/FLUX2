<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Slide;

class MoveAttendeeType extends AbstractType
{
    private $trans;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->trans = $options['translator'];
        $builder
            ->add(
                'fromCourse',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => 'AppBundle:Course\Course',
                    'choices' => $options['fromCourses'],
                    'group_by' => function($val, $key, $index) {
                        return $val->getCourseType();
                    },
                    'label' => 'oktothek.backend_move_from_course_label'
                ]
            )
            ->add(
                'toCourse',
                EntityType::class,
                [
                    'mapped' => false,
                    'class' => 'AppBundle:Course\Course',
                    'choices' => $options['toCourses'],
                    'group_by' => function($val, $key, $index) {
                        return $val->getCourseType();
                    },
                    'choice_label' => function($course) {
                        if ($course->isFull()) {
                            return $this->trans->trans('oktothek.attendee_move_course_full_label', ['%course%' => $course]);
                        } else {
                            return $course;
                        }
                    },
                    'label' => 'oktothek.backend_move_to_course_label'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('fromCourses');
        $resolver->setRequired('toCourses');
        $resolver->setRequired('translator');
    }

    public function getBlockPrefix()
    {
        return 'appbundle_move_attendee';
    }
}
