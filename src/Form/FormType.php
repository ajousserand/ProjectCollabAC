<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search',TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr' =>[
                    'placeholder' => 'rechercher jeux'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=> '<i class="fa-solid fa-magnifying-glass"></i>',
                'attr'=>[
                    'class'=> 'btn btn-warning'
                ]
            ])
            ;
    }

}