<?php

namespace MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use MediaBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MediaBundle\Form\DataTransformer\UserTransformer;

class SeriesUserType extends AbstractType
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', CollectionType::class, [
                    'entry_type'        => HiddenType::class,
                    'allow_add'         => true,
                    'allow_delete'      => true,
                    'attr'              => [
                        'placeholder' => 'search usernames',
                        'url'         => 'oktolab_series_user_by_username'
                    ]
            ]);

        $UserTransformer = new UserTransformer($this->repo);

        $builder->get('users')->addModelTransformer($UserTransformer);
            // ->add('users', UserType::class, [
            //         'entry_type'    => UserType::class,
            //         'allow_add'     => true,
            //         'allow_delete'  => true,
            //         'attr'          => [
            //             'placeholder' => 'search usernames',
            //             'url'         => 'oktolab_series_user_by_username'
            //         ]
            //     ]
            // );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MediaBundle\Entity\Series',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mediabundle_series_user';
    }
}

 ?>
