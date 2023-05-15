<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request, EntityManagerInterface $manager,  UserPasswordHasherInterface $hasher, Security $security): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();
            
            $security->login($user);
            
            $this->addFlash('success', 'Votre compte est bien créer vous pouvez déclarer votre sinistre.');
            return $this->redirectToRoute('form_sinister');
            
        }else if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('danger', 'Il y a des erreurs dans la saisi du formulaire.');
        }

        return $this->render('registration/index.html.twig', ['form' => $form]);
    }
}
