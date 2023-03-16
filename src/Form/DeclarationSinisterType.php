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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerPart', CustomerType::class, [
                'label' => 'Information du client'
            ])
            ->add('sinisterPart', SinisterType::class, [
                'label' => 'Information sur le sinsitre '
            ])
            ->add('submit', SubmitType::class);
    }

}
