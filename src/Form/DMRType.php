<?php

namespace App\Form;

use App\Entity\DMR;
use Bnine\MdEditorBundle\Form\Type\MarkdownType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DMRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', MarkdownType::class, [
                'label' => false,
                'required' => false,
                'markdown_height' => 200,
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['submit_label'] ?? 'Ajouter',
                'attr' => ['class' => 'btn btn-success w-100'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DMR::class,
            'submit_label' => 'Ajouter',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return '';
    }
}
