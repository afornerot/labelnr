<?php

namespace App\Form;

use App\Entity\Weekly;
use Bnine\MdEditorBundle\Form\Type\MarkdownType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeeklyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            'attr' => ['class' => 'btn btn-success no-print me-1'],
        ])

        ->add('date', DateType::class, [
            'label' => 'Date',
        ])

        ->add('nbHour', IntegerType::class, [
            'label' => 'Nombre d\'heures',
        ])

        ->add('description', MarkdownType::class, [
            'label' => 'Description',
            'markdown_height' => 500,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weekly::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => static::class,
            'mode' => 'submit',
        ]);
    }
}
