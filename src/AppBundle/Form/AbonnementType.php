<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AbonnementType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('livestream', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_livestream_label'
                ])
            ->add('newEpisode', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_episode_label'
                ])
            ->add('newPost', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_post_label'
                ])
            ->add('send_mails', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_send_mails_label'
                ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Abonnement'
        ));
    }

    public function getName()
    {
        return 'oktothek_abonnement';
    }
}
