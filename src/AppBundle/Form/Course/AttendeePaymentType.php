<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Model\AcademyService as Status;


class AttendeePaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentStatus', ChoiceType::class,
                [
                    'label' => 'oktothek_attendee_paymentstatus_label',
                    'choices' => [
                        Status::ACADEMY_OPEN_TRANSACTION => Status::ACADEMY_OPEN_TRANSACTION,
                        Status::ACADEMY_CLOSED_TRANSACTION => Status::ACADEMY_CLOSED_TRANSACTION,
                        Status::ACADEMY_REFUND_TRANSACTION => Status::ACADEMY_REFUND_TRANSACTION,
                        Status::ACADEMY_MONEY => Status::ACADEMY_MONEY,
                        Status::ACADEMY_MONEY_CLOSED => Status::ACADEMY_MONEY_CLOSED
                    ]
                ])
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Course\Attendee'
        ]);
    }

    public function getName()
    {
        return 'appbundle_attendee_paymentstatus';
    }
}
