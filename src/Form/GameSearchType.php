<?php

namespace App\Form;

use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
            'label' => 'name',
            'required' =>false,
            'attr' => [
                'placeholder' => 'Recherche par nom'
            ]
            ])
            ->add('price',NumberType::class,[
                'label' => 'prix',
                'required' =>false,
                'attr' => [
                    'placeholder' => 'Recherche par prix'
                ]
            ])
            ->add('publishedAt',DateType::class,[
                'label' => 'date de publication',
                'required' =>false,
                'widget'=>'single_text',
                'years'=> range(date('Y'), date('Y') -30),
                'attr' => [
                    'placeholder' => 'Recherche par date'
                ]
            ])
            ->add('genres',EntityType::class,[
                'class'=> Genre::class,
                'choice_label' => 'name',
                'required' =>false,
                'multiple'=>true,
                'expanded'=>true,
                'attr'=> [
                    'class'=>'checkboxes-template'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
