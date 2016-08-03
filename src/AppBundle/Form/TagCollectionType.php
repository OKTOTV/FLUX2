<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\HighlightTagType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TagCollectionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', CollectionType::class, [
                'entry_type'  => HighlightTagType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ]);

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
    public function getName()
    {
        return 'appbundle_tag_collection';
    }
}
 ?>
