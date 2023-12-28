<?php

namespace App\Controller;

use App\Entity\Requests;
use App\Form\RequestType;
use App\Entity\UserRequest;
use App\Form\UserRequestType;
use App\Service\CallApiService;
use App\Repository\InformationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(InformationRepository $informationRepository): Response
    {
        $informations = $informationRepository->findAll();
        //dd($requests);

        return $this->render('acceuil/index.html.twig', [
            'informations' => $informations
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, ManagerRegistry $doctrine): Response
    {
         //création une instance vide de ayant le format userRequest
         $userRequest = new UserRequest();
         $form = $this->createForm(UserRequestType::class, $userRequest);
         
         $form->handleRequest($request);
         //tester si la requette du formulaire est request
         if ($form->isSubmitted() && $form->isValid()) {
             //Associer le user connecté
             $userRequest->setUser($this->getUser());
             //Mettre Active à false par defaut
             $userRequest->setActive(false);
             //Persister le request
             $em = $doctrine->getManager();
             $em->persist($userRequest);
             $em->flush();
             return $this->redirectToRoute('home');
         }

        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
            'form' => $form->createView(),
        ]);
    }


}
