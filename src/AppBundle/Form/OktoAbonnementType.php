<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class OktoAbonnementType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('livestream', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_livestream_label',
                    'required' => false
                ])
            ->add('newEpisode', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_episode_label',
                    'required' => false
                ])
            ->add('newPost', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_post_label',
                    'required' => false
                ])
            ->add('newCommentOnEpisode', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_comment_on_episode_label',
                    'required' => false
                ])
            ->add('newCommentOnBlogPost', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_new_comment_on_blogpost_label',
                    'required' => false
                ])
            ->add('encodedEpisode', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_encodedEpisode_label',
                    'required' => false
                ])
            ->add('send_mails', CheckboxType::class,
                [
                    'label' => 'oktothek_abonnement_send_mails_label',
                    'required' => false
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
