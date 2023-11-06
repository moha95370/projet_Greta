<?php

namespace App\Controller\Admin;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/place', name: 'admin_place_')]
class PlaceController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository -> findAll();

        return $this->render('admin/place/index.html.twig', [
            'places' => $places,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addPlace(Request $request, ManagerRegistry $doctrine): Response
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $em = $doctrine->getManager();
            $em->persist($place);
            $em->flush();
            return $this->redirectToRoute('admin_place_index');
        }

        return $this->render('admin/place/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter une catégorie de lieu installation:'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updatePlace(Place $place, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_place_index');
        }

        return $this->render('admin/place/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier le lieu'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Place $place, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
       $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
       if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($place);
            $em->flush();
            $this->addFlash('success', 'Lieu supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_place_index');
    }

}
