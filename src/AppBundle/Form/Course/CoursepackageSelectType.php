<?php

namespace AppBundle\Form\Course;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Course\AttendeeType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Form\Course\CoursepackageRadioType;

class CoursepackageSelectType extends AbstractType
{
    private $coursetypes;
    private $translator;

    public function __construct($coursepackage, $translator)
    {
        $this->coursetypes = $coursepackage->getCoursetypes();
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attendee', AttendeeType::class)
            ->add('coursepackageRadioSelections', CollectionType::class, [
                'entry_type' => CoursepackageRadioType::class
            ]);
            foreach ($this->coursetypes as $key => $coursetype) {
                $builder->get('coursepackageRadioSelections')
                ->add('coursepackageRadioSelection_'.$key, CoursepackageRadioType::class,
                ['coursetype' => $coursetype]);
            }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Course\CoursepackageSelect'
        ));
    }

    public function getName()
    {
        return 'appbundle_coursepackage_select';
    }
}
