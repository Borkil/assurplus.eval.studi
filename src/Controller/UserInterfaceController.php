<?php

namespace App\Controller;

use App\Repository\SinisterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserInterfaceController extends AbstractController
{
    #[Route('/moncompte', name: 'app_user_historique')]
    public function index(SinisterRepository $sinisterRepository): Response
    {
        return $this->render('user_interface/index.html.twig', [
            'user' => $this->getUser(),
            'sinisters' => $sinisterRepository->findAllOrderByCreatedAt()
        ]);
    }
}
