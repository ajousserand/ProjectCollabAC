<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class, [
                'label'=>'Nom',
            ] )
            ->add('price', TextType::class, [
                'label'=>'Prix',
            ])
            ->add('publishedAt', TypeDateType::class, [
                'label'=>'Date de publication',
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                
            ])
            ->add('thumbnailCover', TextType::class, [
                'label'=>'Cover'
            ])
            ->add('slug')

            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'multiple'=>true,
                'expanded'=>true,
                'attr'=> [
                    'class'=>'checkboxes-template'
                ]
            ])

            ->add('genres', EntityType::class, [
                'class'=>Genre::class,
                'choice_label'=>'name',
                'multiple'=>true,
                'expanded'=>true,
                'attr'=> [
                    'class'=>'checkboxes-template'
                ]
            ])

            ->add('publisher', EntityType::class, [
                'class'=>Publisher::class,
                'choice_label'=>'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
