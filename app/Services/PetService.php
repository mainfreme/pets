<?php

namespace App\Services;

use App\Enums\Status;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PetService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.petstore_api.base_url');
    }

    /**
     * @param int $petId
     * @return array
     * @throws Exception
     */
    public function getPet(int $petId)
    {
        $response = Http::get($this->apiUrl . "/pet/{$petId}");

        $data = $response->json();
        if ($response->successful() && is_array($data)) {
            return $data;
        }

        throw new Exception('Failed to fetch pets ');
    }

    /**
     * @param string $status
     * @return array
     * @throws Exception
     */
    public function getPets(string $status = 'available'): array
    {
        if (!in_array($status, Status::values(), true)) {
            throw new Exception(sprintf('Status %s is not available', $status));
        }

        $response = Http::get($this->apiUrl . "/pet/findByStatus", [
            'status' => $status
        ]);

        $data = $response->json();
        if ($response->successful() && is_array($data)) {
            return $data;
        }

        throw new Exception('Failed to fetch pets ');
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws Exception
     */
    public function addPet(array $data)
    {
        $response = Http::post($this->apiUrl . "/pet", [
            'name' => $data['name'],
            'status' => $data['status'],
            'category' => ['name' => $data['category_name']],
            'tags' => array_map(function ($tag) {
                return ['name' => $tag['name']];
            }, $data['tags']),
            'photoUrls' => $data['photoUrls'],
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Nie udało się dodać zwierzęcia: ' . $response->body());
    }

    /**
     * @param int $petId
     * @param UploadedFile $file
     * @param string|null $additionalMetadata
     * @return array|void
     */
    public function uploadPetImage(int $petId, UploadedFile $file, ?string $additionalMetadata = null)
    {
        $url = $this->apiUrl . "/pet/{$petId}/uploadImage";

        try {
            $response = Http::attach(
                'file',
                file_get_contents($file->getPathname()),
                $file->getClientOriginalName()
            )
                ->post($url, [
                    'additionalMetadata' => $additionalMetadata,
                ]);
            return $this->handleResponse($response, 'Plik został przesłany pomyślnie.');

        } catch (ConnectionException $exception) {
            Log::error('API Connection Error', ['response' => $exception->getMessage()]);
        }


    }

    /**
     * @param string|null $url
     * @return bool
     */
    public function urlFileExists(?string $url): bool
    {
        if (NULL === $url) {
            return false;
        }

        try {
            $response = Http::head($url);
            return $response->successful();
        } catch (Exception $e) {
            Log::error('Error checking file existence at URL: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * @param int $petId
     * @return array
     */
    public function deletePet(int $petId)
    {
        $response = Http::delete($this->apiUrl . "/pet/{$petId}");

        return $this->handleResponse($response, 'Zwierzę zostało usunięte.');
    }

    /**
     * @param array $petData
     * @return array
     */
    public function setPet(array $petData): array
    {
        $response = Http::put($this->apiUrl . "/pet", $petData);

        return $this->handleResponse($response, 'Zwierzę zostało zaktualizowane.');
    }

    /**
     * @param Response $response
     * @param string $message
     * @return array
     */
    private function handleResponse(Response $response, string $message = ''): array
    {
        if ($response->successful()) {
            return [
                'status' => 'success',
                'data' => $response->json(),
                'message' => $message,
            ];
        }

        $errorData = $response->json();
        Log::error('API Error', ['response' => $errorData]);

        return ApiErrorHandler::handle($errorData);
    }
}
