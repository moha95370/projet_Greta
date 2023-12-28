<?php

namespace App\Controller\Admin;

use App\Entity\UserRequest;
use App\Form\UserRequestType;
use App\Repository\UserRequestRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/userRequest', name: 'admin_userRequest_')]
class UserRequestController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRequestRepository $userRequestRepository): Response
    {
        $usersRequest = $userRequestRepository->findAll();

        return $this->render('admin/user_request/index.html.twig', [
            'usersRequest' => $usersRequest,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addUserRequest(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format catégory
        $userRequest = new UserRequest();

        $form = $this->createForm(UserRequestType::class, $userRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $userRequest->setUser($this->getUser());
            $userRequest->setActive(false);
            $em = $doctrine->getManager();
            $em->persist($userRequest);
            $em->flush();
            return $this->redirectToRoute('admin_userRequest_index');
        }
        
        return $this->render('admin/user_request/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter une demande'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateUserRequest(UserRequest $userRequest, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(UserRequestType::class, $userRequest);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_userRequest_index');
        }

        return $this->render('admin/user_request/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Modifier une demande'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(UserRequest $userRequest, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
       $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
       if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($userRequest);
            $em->flush();
            $this->addFlash('success', 'Demande supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_userRequest_index');
    }

}

