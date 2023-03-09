<?php

namespace App\Controller;

use App\Entity\Sinister;
use App\Form\DeclarationSinisterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SinisterController extends AbstractController
{
    #[Route('/declaration', name: 'form_sinister')]
    public function index(Request $request): Response
    {
        $sinister = new Sinister();

        $form = $this->createForm(DeclarationSinisterType::class, $sinister );
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $sinister = $form->getData();
            $this->addFlash('success', 'Votre déclaration nous a bien été transmise');

        }else if($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
        }


        return $this->render('sinister/index.html.twig', ['form' => $form]);
    }
}