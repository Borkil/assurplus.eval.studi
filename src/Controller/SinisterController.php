<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Sinister;
use App\Form\SinisterType;
use App\Form\DeclarationSinisterType;
use Symfony\Component\Mime\Part\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SinisterController extends AbstractController
{
    
    #[Route('/declaration', name: 'form_sinister')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(SinisterType::class);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
            //récupere les données du sinsitre et les persist

            $sinister = $form->getData();
            $sinister->setUserCustomer($this->getUser());
            $entityManager->persist($sinister);
            $entityManager->flush();
            
            $images = $sinister->getImages();

            // Envoie de l'email vers assurplus 
                $email = (new TemplatedEmail())
                    ->from('admin@assurplus.fr')
                    ->to('francois.lalay@hotmail.fr')
                    ->subject('test envoie mail')
                    ->htmlTemplate('email/email.html.twig')
                    ->context([
                        'sinister' => $sinister,
                        'user' => $user,
                    ]);
                
                foreach ($images as $image)
                {
                    $email->addPart(new DataPart(new File($image->getImageFile()->getRealPath())));
                }


                $mailer->send($email);

                $this->addFlash('success', 'Votre déclaration nous a bien été transmise');
                return $this->redirectToRoute('app_user_historique');
            
        }else if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
        }

        return $this->render('sinister/index.html.twig', ['form' => $form]);
    }

}
