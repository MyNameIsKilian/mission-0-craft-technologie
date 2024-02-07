<?php

namespace App\Controller;

use App\Entity\Research;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    #[Route('/api/adresses', methods:['POST'])]
    public function searchAddress(Request $request, EntityManagerInterface $entityManager) : JsonResponse
    {
        // Retrieve data sent in POST request
        $postData = json_decode($request->getContent(), true);
    
        // Check if adresse field exists
        if (!isset($postData["adresse"])) {
            return new JsonResponse(['message' => 'Le champ "adresse" est requis'], Response::HTTP_BAD_REQUEST);
        }

        // Set URL with encoded address
        $address = $postData["adresse"];
        $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address) . '&autocomplete=1';


        try {
            // Send request to address API
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);
            $bodyString = $response->getContent();
            $bodyArray = json_decode($bodyString, true);
            $finalResult = [];

            // Retrieve only 2 fields (address and city) of the response
            foreach ($bodyArray['features'] as $feature) {
                $tmpArray = [
                    'adresse' => $feature['properties']['label'],
                    'ville' => $feature['properties']['city']
                ];
                $finalResult[] = $tmpArray;
            }

            // Register this research in DB
            // $research = new Research();
            // $research->setAddress($address);
            // $research->setIpAddress($request->getClientIp());

            // $entityManager->persist($research);

            // $entityManager->flush();

            return new JsonResponse($finalResult);
        } catch (\Exception $e) {
            return 'Erreur lors de la requête à l\'API Adresse : ' . $e->getMessage();
        }
    }
}
