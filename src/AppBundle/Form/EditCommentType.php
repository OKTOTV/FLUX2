<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditCommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'text',
                TextareaType::class,
                [
                    'attr' => [
                        'placeholder' => 'oktothek.comment_placeholder_text'
                    ],
                    'label' => 'oktothek.edit_comment_label']
            )->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'oktothek.comment_update_button',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            )->add(
                'delete',
                SubmitType::class,
                [
                    'label' => 'oktothek.comment_delete_button',
                    'attr' => ['class' => 'btn btn-link']
                ]
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_edit_comment';
    }
}
