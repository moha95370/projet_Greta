<?php

namespace App\Controller\Dashboard;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard', name: 'dashboard_vehicle_')]
class VehicleController extends AbstractController
{
    #[Route('/dashboard/vehicle', name: 'index')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $user = $this->getUser();

        $vehicles = $vehicleRepository->findBy(['user' => $user]);

        return $this->render('dashboard/vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard/vehicle/add', name: 'add')]
    public function addVehicle(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format Station
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        
        $form->handleRequest($request);
        //tester si la requette du formulaire est station
        if ($form->isSubmitted() && $form->isValid()) {
            //Associer le user connecté
            $vehicle->setUser($this->getUser());
            //Mettre Active à false par defaut
            $vehicle->setActive(false);
            //Persister la station
            $em = $doctrine->getManager();
            $em->persist($vehicle);
            $em->flush();
            return $this->redirectToRoute('dashboard_vehicle_index');
        }

        return $this->render('dashboard/vehicle/add.html.twig', [
            // Passer le formulaire à la vue
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/vehicle/update/{id}', name: 'update')]
    public function updateVehicle(Vehicle $vehicle, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('dashboard_vehicle_index');
        }

        return $this->render('dashboard/vehicle/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier vehicule'
        ]);
    }
}
