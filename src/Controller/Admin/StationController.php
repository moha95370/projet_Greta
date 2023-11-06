<?php

namespace App\Controller\Admin;

use App\Entity\Station;
use App\Form\Station2Type;
use App\Repository\StationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/station', name: 'admin_station_')]
class StationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(StationRepository $stationRepository): Response
    {
        $stations = $stationRepository->findAll();

        return $this->render('admin/station/index.html.twig', [
            'stations' => $stations,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addStation(Request $request, ManagerRegistry $doctrine): Response
    {
        //création une instance vide de ayant le format post
        $station = new Station();

        $form = $this->createForm(Station2Type::class, $station);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//tester si la requette du formulaire est post
            $station->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($station);
            $em->flush();
            return $this->redirectToRoute('admin_station_index');
        }
        
        return $this->render('admin/station/add.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajouter une borne'
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateStation(Station $station, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(Station2Type::class, $station);

        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_station_index');
        }

        return $this->render('admin/station/add.html.twig', [//la redirection sur la meme vue
            'form' => $form->createView(),
            'h1' => 'Modifier une borne'
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Station $station, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($station);
            $em->flush();
            $this->addFlash('success', 'Borne supprimée !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_station_index');
    }
}
