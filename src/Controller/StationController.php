<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Station;
use App\Form\StationType;
use App\Form\Station2Type;
use App\Repository\StationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StationController extends AbstractController
{
    #[Route('/station', name: 'app_station')]
    public function index(StationRepository $stationRepository): Response
    {
    
        $user = $this->getUser();
        $stations = $stationRepository->findBy(['user' => $user]);

        return $this->render('station/index.html.twig', [
            'stations' => $stations,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/station/add', name: 'station_add')]
    public function addStation(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format Station
        $station = new Station();
        $form = $this->createForm(StationType::class, $station);
        
        $form->handleRequest($request);
        //tester si la requette du formulaire est station
        if ($form->isSubmitted() && $form->isValid()) {
            //Associer le user connecté
            $station->setUser($this->getUser());
            //Mettre Active à false par defaut
            $station->setActive(false);
            //Persister la station
            $em = $doctrine->getManager();
            $em->persist($station);
            $em->flush();
            return $this->redirectToRoute('app_station');
        }

        return $this->render('station/add.html.twig', [
            // Passer le formulaire à la vue
            'form' => $form->createView(),
        ]);
    }

    #[Route('/station/update/{id}', name: 'station_update')]
    public function updateStation(Station $station, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(StationType::class, $station);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_station');
        }

        return $this->render('station/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier un article'
        ]);
    }
    

}
