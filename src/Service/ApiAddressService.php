namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ApiAddress
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public function searchAddress($address)
    {
        $url = 'https://api-adresse.data.gouv.fr/search/?q=' . urlencode($address) . '&autocomplete=1';

        try {
            $response = $this->httpClient->request('GET', $url);

            // Récupérer le contenu de la réponse et le traiter selon vos besoins
            $content = $response->getContent();

            return $content;
        } catch (\Exception $e) {
            // Gérer les erreurs
            return 'Erreur lors de la requête à l\'API Adresse : ' . $e->getMessage();
        }
    }
}