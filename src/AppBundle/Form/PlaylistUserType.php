<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PlaylistUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'oktothek.playlist_name_label'])
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'oktothek.playlist_description_label',
                    'required' => false
                ]
            )
            ->add('items', CollectionType::class, [
                'entry_type' => PlaylistItemType::class,
                'allow_add'  => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'oktothek.playlist_items_label'
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Playlist'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_playlist_user';
    }
}
