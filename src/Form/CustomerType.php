<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numberCustomer', TextType::class, [
                'label' => 'Numéro client'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('lastname', TextType::class, [
                'label' =>  'Nom de famille'
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Adresse Email'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
