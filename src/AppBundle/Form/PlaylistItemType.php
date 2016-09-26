<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use AppBundle\Form\DataTransformer\PlaylistItemTransformer;

class PlaylistItemType extends AbstractType
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
        $episodeItemTransformer = new PlaylistItemTransformer($this->repository);

        $builder
            ->add('sortnumber')
            ->add('episode', TextType::class);

        $builder->get('episode')->addModelTransformer($episodeItemTransformer);

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oktolab\MediaBundle\Entity\Playlistitem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_playlist_item';
    }
}
