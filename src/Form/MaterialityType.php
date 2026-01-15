<?php

namespace App\Form;

use App\Entity\Materiality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            'attr' => ['class' => 'btn btn-success no-print me-1'],
        ])

        ->add('code', TextType::class, [
            'label' => 'Code',
            'disabled' => 'submit' !== $options['mode'],
        ])

        ->add('title', TextType::class, [
            'label' => 'Titre',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiality::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => static::class,
            'mode' => 'submit',
        ]);
    }
}
