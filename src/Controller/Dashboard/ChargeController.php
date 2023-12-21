<?php

namespace App\Controller\Dashboard;

use App\Entity\Charge;
use App\Form\ChargeType;
use App\Repository\ChargeRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard', name: 'dashboard_charge_')]
class ChargeController extends AbstractController
{
    #[Route('/dashboard/charge', name: 'index')]
    public function index(ChargeRepository $chargeRepository): Response
    {
        $charges = $chargeRepository->findAll();

        return $this->render('dashboard/charge/index.html.twig', [
            'charges' => $charges,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard/charge/add', name: 'add')]
    public function addVehicle(Request $request, ManagerRegistry $doctrine): Response
    {
      
        $charge = new Charge();
        $form = $this->createForm(ChargeType::class, $charge);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Associer le user connecté
            // $charge->setUser($this->getUser());
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
