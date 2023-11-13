<?php

namespace App\Controller;

use App\Entity\Station;
use App\Form\StationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StationController extends AbstractController
{
    #[Route('/station', name: 'app_station')]
    public function index(): Response
    {
        return $this->render('station/view.html.twig', [
            'controller_name' => 'StationController',
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
            return $this->redirectToRoute('home');
        }

        return $this->render('station/add.html.twig', [
            // Passer le formulaire à la vue
            'form' => $form->createView(),
        ]);
    }
    

}
