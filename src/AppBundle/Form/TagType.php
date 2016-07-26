<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class TagType extends AbstractType
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TagTransformer($this->repository);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The tag does not exist',
            'type'  => HiddenType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ));
    }

    public function getParent()
    {
        return 'collection';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'tag';
    }

    public function getName()
    {
        return 'tag';
    }
}
