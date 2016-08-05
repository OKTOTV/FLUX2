<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\TagTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'The tag does not exist',
            'type'  => HiddenType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ]);
    }

    public function getParent()
    {
        return CollectionType::class;
    }

    public function getName()
    {
        return 'tag';
    }
}
