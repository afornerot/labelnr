<?php

namespace App\Form;

use App\Entity\ActionPlan;
use App\Entity\TIR;
use App\Enum\ActionPlanStatusEnum;
use Bnine\MdEditorBundle\Form\Type\MarkdownType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-success no-print me-1'],
            ])

            ->add('priority', IntegerType::class, [
                'label' => 'Priorité',
            ])

            ->add('title', null, [
                'label' => 'Titre',
            ])

            ->add('status', EnumType::class, [
                'label' => 'Statut',
                'class' => ActionPlanStatusEnum::class,
            ])

            ->add('progress', IntegerType::class, [
                'label' => '% Avancement',
            ])

            ->add('description', MarkdownType::class, [
                'label' => 'Description',
                'markdown_height' => 500,
            ])

            ->add('tirs', EntityType::class, [
                'label' => 'TIRs liés',
                'class' => TIR::class,
                'choice_label' => 'code',
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'select2'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActionPlan::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => static::class,
            'mode' => 'submit',
        ]);
    }
}
