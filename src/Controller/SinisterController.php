<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Sinister;
use App\Form\CustomerType;
use App\Form\SinisterType;
use App\Form\DeclarationSinisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SinisterController extends AbstractController
{
    
    #[Route('/declaration', name: 'form_sinister')]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(DeclarationSinisterType::class);
        $form->handleRequest($request);

       
        if($form->isSubmitted() && $form->isValid())
        {
            //récupere les données du sinsitre et les persist
            $sinister = $form->getData()['sinisterPart'];
            $entityManager->persist($sinister);

            //récupere les données du customer
            //controle si il existe ou pas en bdd

            $customer = new Customer();
            $customer = $form->getData()['customerPart'];

            $testEntityIsUnique = $entityManager->getRepository(Customer::class)->findOneBy(['numberCustomer' => $customer->getNumberCustomer()]);

            if($testEntityIsUnique){
                // met à jour customer en bdd
                // ajoute le sinistre à la collection
                $testEntityIsUnique
                    ->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setMail($customer->getMail())
                    ->setPhoneNumber($customer->getPhoneNumber())
                    ->addSinister($sinister);

                $entityManager->flush();

                $this->addFlash('success', 'Votre déclaration nous a bien été transmise');
                return $this->redirectToRoute('app_home_page');
            }else{
                // creer le customer en bdd
                // ajoute le sinistre au customer 
                // enregristre le customer et le sinistre en BDD
                $customer->addSinister($sinister);
                $entityManager->persist($customer);
                $entityManager->flush();

                $this->addFlash('success', 'Votre déclaration nous a bien été transmise');
                return $this->redirectToRoute('app_home_page');
            }
        }else if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
        }

        return $this->render('sinister/index.html.twig', ['form' => $form]);
    }

    // #[Route('/declaration', name: 'form_sinister')]
    // public function index(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $sinister = new Sinister();

    //     $form = $this->createForm(DeclarationSinisterType::class, $sinister );

    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid())
    //     {
    //         $sinister = $form->getData();
    //         $entityManager->persist($sinister);
            
    //         $entityManager->flush();
            

    //         $this->addFlash('success', 'Votre déclaration nous a bien été transmise');
    //         return $this->redirectToRoute('app_home_page');

    //     }else if($form->isSubmitted() && !$form->isValid())
    //     {
    //         $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
    //     }


    //     return $this->render('sinister/index.html.twig', ['form' => $form]);
    // }
}
