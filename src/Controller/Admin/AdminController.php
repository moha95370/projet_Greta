<?php

namespace App\Controller\Admin;

use App\Repository\StationRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(StationRepository $stationRepository, UserRepository $userRepository): Response
    {
        $stations = $stationRepository->findAll();
        
        $counts = $userRepository->nbAllSubjects();
        
        return $this->render('admin/index.html.twig', [
            'stations' => $stations,
            'counts' => $counts
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profil(StationRepository $stationRepository, UserRepository $userRepository): Response
    {
        
        return $this->render('admin/profile/index.html.twig', [
        ]);
    }
}
