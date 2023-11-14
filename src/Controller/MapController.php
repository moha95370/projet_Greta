<?php

namespace App\Controller;


use App\Controller\MapapiController;
use App\Repository\StationRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(StationRepository $stationRepository): Response
    {
        //Utilisation du composant HttpClient de Symfony pour effectuer une requête GET à l'URL. 
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://odre.opendatasoft.com/api/explore/v2.1/catalog/datasets/bornes-irve/records?limit=100&refine=departement%3A%22Paris%22');
        //Les résultats de la requête sont ensuite convertis en tableau à l'aide de la méthode toArray()
        $stationsFromAPI = $response->toArray();

        $stationsFromDB = $stationRepository->findAll();

        // Combiner les données
        $combinedData = array_merge($stationsFromAPI, $stationsFromDB);

        return $this->render('map/index.html.twig', [
            'stations' => $combinedData,
        ]);
    }
}