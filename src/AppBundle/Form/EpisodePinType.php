<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EpisodePinType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('episode', 'entity', array('class' => 'AppBundle:Episode', 'choice_label' => 'name'))//, array('class' => 'AppBundle\Entity\Asset'))
            ->add('title')//, array('class' => 'AppBundle\Entity\Asset'))
            ->add('description')
            ->add('onlineAt', 'datetime')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\EpisodePin'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_episodepin';
    }
}
