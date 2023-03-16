<?php

namespace App\Form;

use App\Entity\Sinister;
use App\Form\CustomerType;
use App\Form\SinisterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Test\FormBuilderInterface as TestFormBuilderInterface;

class DeclarationSinisterType extends AbstractType
{
    // public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $builder
    //         ->add('adressOfSinister', TextType::class)
    //         ->add('description', TextareaType::class)
    //         ->add('numberRegistration', TextType::class)
    //         ->add('submit', SubmitType::class,[
    //             'label' => 'Valider'
    //         ])
    //         ;
    // }
    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Sinister::class,
    //     ]);
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerPart', CustomerType::class)
            ->add('sinisterPart', SinisterType::class)
            ->add('submit', SubmitType::class);
    }

}
