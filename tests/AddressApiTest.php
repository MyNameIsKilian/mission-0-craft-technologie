<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;

class AddressApiTest extends WebTestCase
{
    public function testGetAddressesFromApi()
    {
        // Create client to request address API
        $httpClient = HttpClient::create();
        $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode('impasse des cocotiers') . '&autocomplete=1';
        $response = $httpClient->request('GET', $url);

        // Check if status code equals 200
        $this->assertEquals(200, $response->getStatusCode());

        // Check if response contains estimated content string
        $this->assertEquals(
            $response->getContent(),
            '{"type":"FeatureCollection","version":"draft","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[55.54941,-21.275868]},"properties":{"label":"Impasse des Cocotiers 97430 Le Tampon","score":0.9674545454545455,"id":"97422_0078","name":"Impasse des Cocotiers","postcode":"97430","citycode":"97422","x":349508.73,"y":7646629.44,"city":"Le Tampon","context":"974, La Réunion","type":"street","importance":0.642,"street":"Impasse des Cocotiers"}},{"type":"Feature","geometry":{"type":"Point","coordinates":[55.542465,-21.365745]},"properties":{"label":"Impasse des Cocotiers 97429 Petite-Île","score":0.9613872727272726,"id":"97405_0115","name":"Impasse des Cocotiers","postcode":"97429","citycode":"97405","x":348880.15,"y":7636673.1,"city":"Petite-Île","context":"974, La Réunion","type":"street","importance":0.57526,"street":"Impasse des Cocotiers"}},{"type":"Feature","geometry":{"type":"Point","coordinates":[55.814539,-21.143829]},"properties":{"label":"Impasse des Cocotiers 97439 Sainte-Rose","score":0.9551136363636362,"id":"97419_0294","name":"Impasse des Cocotiers","postcode":"97439","citycode":"97419","x":376908.32,"y":7661474.9,"city":"Sainte-Rose","context":"974, La Réunion","type":"street","importance":0.50625,"street":"Impasse des Cocotiers"}},{"type":"Feature","geometry":{"type":"Point","coordinates":[-1.428194,46.810238]},"properties":{"label":"Impasse des cocotiers 85170 Bellevigny","score":0.9542527272727273,"id":"85019_0482","name":"Impasse des cocotiers","postcode":"85170","citycode":"85019","oldcitycode":"85279","x":362512.07,"y":6643920.34,"city":"Bellevigny","oldcity":"Saligny","context":"85, Vendée, Pays de la Loire","type":"street","importance":0.49678,"street":"Impasse des cocotiers"}},{"type":"Feature","geometry":{"type":"Point","coordinates":[-61.775699,16.162802]},"properties":{"label":"Impasse des Cocotiers 97125 Bouillante","score":0.9536363636363635,"id":"97106_0558","name":"Impasse des Cocotiers","postcode":"97125","citycode":"97106","x":630891.77,"y":1787333.09,"city":"Bouillante","context":"971, Guadeloupe","type":"street","importance":0.49,"street":"Impasse des Cocotiers"}}],"attribution":"BAN","licence":"ETALAB-2.0","query":"impasse des cocotiers","limit":5}'
        );
    }
}
