<?php

namespace App\Controller\Admin;

use App\Entity\Information;
use App\Form\InformationType;
use App\Repository\InformationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/information', name: 'admin_information_')]
class InformationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(InformationRepository $informationRepository): Response
    {
        $informations = $informationRepository->findAll();

        return $this->render('admin/information/index.html.twig', [
            'informations' => $informations,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addInformation(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format Information
        $information = new Information();

        $form = $this->createForm(InformationType::class, $information);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est Information
            $information->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($information);
            $em->flush();
            return $this->redirectToRoute('admin_information_index');
        }
        
        return $this->render('admin/information/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter une information'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateInformation(Information $information, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(InformationType::class, $information);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_information_index');
        }

        return $this->render('admin/information/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier une information'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Information $information, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($information);
            $em->flush();
            $this->addFlash('success', 'Information supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_information_index');
    }
}
