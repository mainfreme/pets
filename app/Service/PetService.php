<?php

namespace App\Service;

use App\Enums\Status;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PetService
{

    /**
     * @param $status
     * @return array
     * @throws \Exception
     */
    public function getPets(string $status = 'available')
    {
        if (!in_array($status, Status::values(), true)) {
            throw new \Exception(sprintf('Status %s is not available', $status));
        }

        $response = Http::get(config('services.petstore_api.base_url')."/pet/findByStatus", [
            'status' => $status
        ]);

        $data = $response->json();
        if ($response->successful() && is_array($data)) {
            return $data;
        }

        throw new \Exception('Failed to fetch pets ');
    }

    /**
     * @param string $url
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
        } catch (\Exception $e) {
            Log::error('Error checking file existence at URL: ' . $e->getMessage());
            return false;
        }
    }

}
