<?php

namespace AppBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Bprs\AssetBundle\Form\Type\AssetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Entity\Slide;

class SlideType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'oktothek.backend_slide_name_label'])
            ->add('description', TextType::class, ['label' => 'oktothek.backend_slide_description_label'])
            ->add('link', UrlType::class, ['label' => 'oktothek.backend_slide_link_label'] )
            ->add('onlineAt', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'oktothek.backend_slide_onlineAt_label'
            ])
            ->add('asset', AssetType::class, ['label' => 'oktothek.backend_slide_asset_label'])
            ->add('slideType', ChoiceType::class, [
                'label' => 'oktothek.backend_slide_type_label',
                'choices' => [
                    'oktothek_slide_type_episode' => Slide::TYPE_EPISODE,
                    'oktothek_slide_type_news'    => Slide::TYPE_NEWS
                ],
                'choices_as_values' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Slide',
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_slide';
    }
}
