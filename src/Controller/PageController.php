<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Repository\InformationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(InformationRepository $informationRepository): Response
    {
        $informations = $informationRepository->findAll();
        //dd($posts);

        return $this->render('acceuil/index.html.twig', [
            'informations' => $informations
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

}
