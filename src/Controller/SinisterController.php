<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\DeclarationSinisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class SinisterController extends AbstractController
{
    
    #[Route('/declaration', name: 'form_sinister')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $form = $this->createForm(DeclarationSinisterType::class);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
            //récupere les données du sinsitre et les persist
            $sinister = $form->getData()['sinisterPart'];
            $entityManager->persist($sinister);

            //récupere les données du customer
            $customer = new Customer();
            $customer = $form->getData()['customerPart'];

            //controle si il existe ou pas en bdd
            $testEntityIsUnique = $entityManager->getRepository(Customer::class)->findOneBy(['numberCustomer' => $customer->getNumberCustomer()]);

            if($testEntityIsUnique){
                // si customer existe deja alors il met à jour customer en bdd
                // et ajoute le sinistre à la collection
                $testEntityIsUnique
                    ->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setMail($customer->getMail())
                    ->setPhoneNumber($customer->getPhoneNumber())
                    ->addSinister($sinister);

                $entityManager->flush();

                $email = (new TemplatedEmail())
                    ->from('admin@assurplus.fr')
                    ->to('francois.lalay@hotmail.fr')
                    ->subject('test envoie mail')
                    ->htmlTemplate('email/email.html.twig')
                    ->context([
                        'sinister' => $sinister
                    ]);

                $mailer->send($email);    

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

}
