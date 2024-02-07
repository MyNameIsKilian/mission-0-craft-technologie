<?php

namespace App\Service;

use DateTime;
use Symfony\Component\HttpClient\HttpClient;

class AddressApiService
{
    public function getAddressSuggestions(string $address): array
    {
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

            return $finalResult;
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la requête à l\'API Adresse : ' . $e->getMessage());
        }
    }
}
