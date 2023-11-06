<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
      
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profile/edit', name: 'profil_edit')]
    public function editProfil(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Profil mis à jour !');
            return $this->redirectToRoute('app_profile');
        } 
        
        return $this->render('profile/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
