<?php

namespace App\Controller;

use App\Repository\ChantierRepository;
use App\Repository\PointageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(UserRepository $userRepository, ChantierRepository $chantierRepository, PointageRepository $pointageRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'users_count' => count($userRepository->findAll()),
            'pointages_count' => count($pointageRepository->findAll()),
            'chantiers_count' => count($chantierRepository->findAll())
        ]);
    }
}
