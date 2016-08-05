<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\HighlightTagType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\DataTransformer\TagSlugTransformer;

class TagCollectionType extends AbstractType
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tagTransformer = new TagSlugTransformer($this->repo);

        $builder
            ->add('tags', CollectionType::class, [
                'entry_type'  => HighlightTagType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);
        $builder->get('tags')->addModelTransformer($tagTransformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TagCollection'
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_tag_collection';
    }
}
 ?>
