<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('name', TextType::class,
                [
                    'label' => 'oktothek_attendee_name_label',
                    'attr' => ['placeholder' => 'oktothek_attende_name_placeholder']
                ])
            ->add('surname', TextType::class,
                [
                    'label' => 'oktothek_attendee_surname_label',
                    'attr' => ['placeholder' => 'oktothek_attende_surname_placeholder']
                ])
            ->add('email', EmailType::class,
                [
                    'label' => 'oktothek_attendee_email_label',
                    'attr' => ['placeholder' => 'oktothek_attende_mail_placeholder']
                ])
            ->add('tel', TextType::class,
                [
                    'label' => 'oktothek_attendee_tel_label',
                    'attr' => ['placeholder' => 'oktothek_attende_tel_placeholder']
                ])
            ->add('reducedEligible', CheckboxType::class,
                [
                    'label' => 'oktothek_attendee_reduced_label'
                ])
            ->add('info', TextareaType::class,
                [
                    'label' => 'oktothek_attendee_info_label',
                    'attr' => ['placeholder' => 'oktothek_attendee_info_placeholder']
                ])
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
