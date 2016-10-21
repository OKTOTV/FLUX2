<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CoursepackageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'oktothek.backend_coursepackage_name_label'])
            ->add('description', TextareaType::class, ['label' => 'oktothek.backend_coursepackage_description_label'])
            ->add('price', MoneyType::class, ['label' => 'oktothek.backend_coursepackage_price_label'])
            ->add('price_reduced', MoneyType::class, ['label' => 'oktothek.backend_coursepackage_priceReduced_label'])
            ->add('is_active', CheckboxType::class, ['label' => 'oktothek.backend_course_isActive_label'])
            ->add('coursetypes', EntityType::class, [
                'class' => "AppBundle:Course\Coursetype",
                'choices' => $options['coursetypes'],
                'expanded' => true,
                'multiple' => true,
                'label' => 'oktothek.backend_coursepackage_coursetypes_label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course\Coursepackage',
            'coursetypes' => []
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_course';
    }
}
