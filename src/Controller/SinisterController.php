<?php

namespace App\Controller;

use App\Entity\Sinister;
use App\Form\DeclarationSinisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SinisterController extends AbstractController
{
    #[Route('/declaration', name: 'form_sinister')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sinister = new Sinister();

        $form = $this->createForm(DeclarationSinisterType::class, $sinister );

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $sinister = $form->getData();
            $entityManager->persist($sinister);
            
            $entityManager->flush();
            

            $this->addFlash('success', 'Votre déclaration nous a bien été transmise');
            return $this->redirectToRoute('app_home_page');

        }else if($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
        }


        return $this->render('sinister/index.html.twig', ['form' => $form]);
    }
}
