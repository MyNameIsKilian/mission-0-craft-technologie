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
        $postData = json_decode($request->getContent(), true);
        dump($request->getClientIp());
    
        // Vérifier si le champ "adresse" existe dans les données envoyées
        if (!isset($postData["adresse"])) {
            return new JsonResponse(['message' => 'Le champ "adresse" est requis'], Response::HTTP_BAD_REQUEST);
        }

        $address = $postData["adresse"];
        $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address) . '&autocomplete=1';
        dump($url);

        try {
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);
            $bodyString = $response->getContent();
            $bodyArray = json_decode($bodyString, true);
            $finalResult = [];
            foreach ($bodyArray['features'] as $feature) {
                $tmpArray = [
                    'adresse' => $feature['properties']['label'],
                    'ville' => $feature['properties']['city']
                ];
                $finalResult[] = $tmpArray;
            }
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
