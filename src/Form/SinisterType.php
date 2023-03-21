<?php

namespace App\Form;

use App\Entity\Sinister;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SinisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adressOfSinister', TextType::class, [
                'label' => 'Adresse du sinistre'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du sinistre'
            ])
            ->add('numberRegistration', TextType::class, [
                'label' => 'Numéro d\'immatriculation de votre voiture'
            ])
            ->add('imagesFiles', FileType::class, [
                'required'=> true,
                'multiple'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sinister::class,
        ]);
    }
}
