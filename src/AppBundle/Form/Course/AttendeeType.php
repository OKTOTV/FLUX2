<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AttendeeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'oktothek_attendee_name_label'])
            ->add('surname', TextType::class, ['label' => 'oktothek_attendee_surname_label'])
            ->add('email', EmailType::class, ['label' => 'oktothek_attendee_email_label'])
            ->add('tel', TextType::class, ['label' => 'oktothek_attendee_tel_label'])
            ->add('reducedEligible', CheckboxType::class, ['label' => 'oktothek_attendee_reduced_label'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course\Attendee'
        ));
    }

    public function getName()
    {
        return 'appbundle_attendee';
    }
}
