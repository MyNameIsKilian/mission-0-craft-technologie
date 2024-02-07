<?php

namespace App\Tests;

use App\Controller\ApiController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AddressApiTest extends WebTestCase
{
    public function testGetAddressesFromApi()
    {
        // Create client to request address API
        $controller = new ApiController();
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '127.0.0.1'], json_encode(['adresse' => 'impasse des cocotiers']));
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);

        // Load AddressApiService
        $client = static::createClient();
        $container = $client->getContainer();
        $addressApiService = $container->get('App\Service\AddressApiService');

        // Call controller function
        $response = $controller->searchAddress($request, $entityManagerMock, $addressApiService);

        // Check if status code equals 200
        $this->assertEquals(200, $response->getStatusCode());

        // Check response type, expect json
        $this->assertJson($response->getContent());

        // Check if response contains minimum 1 address 
        $responseData = json_decode($response->getContent(), true);
        $this->assertGreaterThan(0, count($responseData));

        // Check estimated response content
        $this->assertEquals($response->getContent(), '[{"adresse":"Impasse des Cocotiers 97430 Le Tampon","ville":"Le Tampon"},{"adresse":"Impasse des Cocotiers 97429 Petite-\u00cele","ville":"Petite-\u00cele"},{"adresse":"Impasse des Cocotiers 97439 Sainte-Rose","ville":"Sainte-Rose"},{"adresse":"Impasse des cocotiers 85170 Bellevigny","ville":"Bellevigny"},{"adresse":"Impasse des Cocotiers 97125 Bouillante","ville":"Bouillante"}]');

        // Check that each object in response contains address and city keys
        foreach ($responseData as $address) {
            $this->assertArrayHasKey('adresse', $address);
            $this->assertArrayHasKey('ville', $address);
        }

        // Add test to check if research is saved in DB
    }
}
