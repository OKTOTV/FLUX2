<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Validator\Constraints as Assert;

class AttendeeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $title_choices = [
            'oktothek_attendee_title_madam' => 1,
            'oktothek_attendee_title_sir' => 2,
        ];
        // If we ever want to random shuffle titles, uncomment this line.
        // uksort($title_choices, function ($a, $b) {return mt_rand(-10, 10);});

        $builder
            ->add('title', ChoiceType::class, [
                'label' => 'oktothek_attendee_title_label',
                'placeholder' => 'oktothek_attendee_title_placeholder',
                'choices' => $title_choices
            ])
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
            ->add('adress', TextType::class, [
                'label' => 'oktothek_attendee_adress_label',
                'attr' => ['placeholder' => 'oktothek_attendee_adress_placeholder']
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'oktothek_attendee_plz_label',
                'attr' => ['placeholder' => 'oktothek_attendee_plz_placeholder']
            ])
            ->add('city', TextType::class, [
                'label' => 'oktothek_attendee_city_label',
                'attr' => ['placeholder' => 'oktothek_attendee_city_placeholder']
            ])
            ->add('country', CountryType::class, [
                'label' => 'oktothek_attendee_country_label',
                'placeholder' => 'oktothek_attendee_country_placeholder',
                'preferred_choices' => [
                    Intl::getRegionBundle()->getCountryNames()['AT'] => 'AT',
                    Intl::getRegionBundle()->getCountryNames()['DE'] => 'DE',
                    Intl::getRegionBundle()->getCountryNames()['CH'] => 'CH'
                ]
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
                    'label' => 'oktothek_attendee_bookCourse_reduced_label',
                    'required' => false
                ])
            ->add('agb', CheckboxType::class, [
                'label' => 'oktothek_attendee_agb_label',
                'mapped' => false,
                'constraints' => [new Assert\IsTrue()]
            ])
            ->add('info', TextareaType::class,
                [
                    'label' => 'oktothek_attendee_info_label',
                    'attr' => ['placeholder' => 'oktothek_attendee_info_placeholder'],
                    'required' => false
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
