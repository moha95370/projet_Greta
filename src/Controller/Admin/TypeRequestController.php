<?php

namespace App\Controller\Admin;

use App\Entity\TypeRequest;
use App\Form\TypeRequestType;
use App\Form\TypeRequest2Type;
use App\Repository\TypeRequestRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/typeRequest', name: 'admin_typeRequest_')]
class TypeRequestController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TypeRequestRepository $typeRequestRepository): Response
    {
        $typesRequest = $typeRequestRepository->findAll();

        return $this->render('admin/type_request/index.html.twig', [
            'typesRequest' => $typesRequest,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addTypeRequest(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format catégory
        $typeRequest = new TypeRequest();

        $form = $this->createForm(TypeRequestType::class, $typeRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $em = $doctrine->getManager();
            $em->persist($typeRequest);
            $em->flush();
            return $this->redirectToRoute('admin_typeRequest_index');
        }
        
        return $this->render('admin/type_request/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter un type de demande'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateTypeRequest(TypeRequest $typeRequest, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TypeRequestType::class, $typeRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_typeRequest_index');
        }

        return $this->render('admin/type_request/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Modifier une type de demande'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(TypeRequest $typeRequest, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
       $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
       if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($typeRequest);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_typeRequest_index');
    }

}
