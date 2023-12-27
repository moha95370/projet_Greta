<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Entity\Charge;
use App\Entity\Vehicle;
use App\Form\ChargeType;
use App\Repository\UserRepository;
use App\Repository\ChargeRepository;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard', name: 'dashboard_charge_')]
class ChargeController extends AbstractController
{
    #[Route('/charge', name: 'index')]
    public function index(ChargeRepository $chargeRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();        
  
        $charges = $chargeRepository->findChargesUser($userId);
        
        foreach ($charges as $charge) {
            // Calcul du prix par heure
            $charge->pricePerHour = $charge->getPrice(); // Ajoutez votre logique ici
            
            // Calcul du prix total
            $interval = $charge->getDuration()->diff($charge->getCreatedAt());
            $hours = $interval->h + $interval->i / 60;
            $charge->totalPrice = $charge->pricePerHour * $hours;
        }

        return $this->render('dashboard/charge/index.html.twig', [
            'charges' => $charges,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/charge/add', name: 'add')]
    public function addCharge(Request $request, ManagerRegistry $doctrine): Response
    {
        
        $charge = new Charge();
        $form = $this->createForm(ChargeType::class, $charge);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Associer le user connecté
            //$charge->setUser($this->getUser());
            $charge->setPayment(false);
            $em = $doctrine->getManager();
            $em->persist($charge);
            $em->flush();
            return $this->redirectToRoute('dashboard_charge_index');
        }

        return $this->render('dashboard/charge/add.html.twig', [
            // Passer le formulaire à la vue
            'form' => $form->createView(),
        ]);
    }
}
