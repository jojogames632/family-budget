<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefUpdateBudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plannedAmount', NumberType::class, [
                'required' => true,
            ])
            ->add('deadlineNumber', NumberType::class, [
                'required' => true,
            ])
            ->add('deadlineWord', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'jours' => 'jours',
                    'semaine' => 'semaine',
                    'mois' => 'mois',
                    'trimestre' => 'trimestre',
                    'semestre' => 'semestre',
                    'an' => 'an'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
