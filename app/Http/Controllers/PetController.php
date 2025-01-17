<?php

namespace App\Http\Controllers;

use App\Service\PetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    private PetService $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * Display a listing of pets.
     */
    public function index(string $status = 'available')
    {
        try {
            $data = $this->petService->getPets($status);
        } catch (\Exception $e) {
            return view('error', ['exception' => $e]);
        }
        return view('index', compact('data'));
    }
}
