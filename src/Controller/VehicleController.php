<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VehicleController extends AbstractController
{
    #[Route('/vehicle', name: 'app_vehicle')]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $user = $this->getUser();

        $vehicles = $vehicleRepository->findBy(['user' => $user]);

        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/vehicle/add', name: 'vehicle_add')]
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
            return $this->redirectToRoute('app_vehicle');
        }

        return $this->render('vehicle/add.html.twig', [
            // Passer le formulaire à la vue
            'form' => $form->createView(),
        ]);
    }
}
