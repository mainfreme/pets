<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Services\PetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

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

    public function create()
    {
        return view('form', ['action' => __METHOD__, 'status' => Status::values()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => ['required', new Enum(Status::class)],
            'category_name' => 'string',
            'tags' => 'array',
            'tags.*.name' => 'string',
            'photoUrls' => 'nullable|array',
            'photoUrls.*' => 'url',
        ]);

        try {
            $pet = $this->petService->addPet($request->all());

            return redirect()->route('pets.create')->with('success', 'Pet added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('pets.create')->with('error', $e->getMessage());
        }
    }

    public function edit($petId)
    {
        try {
            $pet = $this->petService->getPet($petId);
        } catch (\Exception $e) {
            Log::error('');
            return redirect()->back()->withErrors(['error' => '']);
        }

        return view('form', ['pet' => $pet, 'action' => __METHOD__, 'status' => Status::values()]);
    }

    /**
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => ['required', new Enum(Status::class)],
            'category_name' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*.name' => 'nullable|string',
        ]);

        $result = $this->petService->setPet(array_merge(['id' => $id], $validated));

        if ($result['status'] === 'success') {
            return redirect()->route('pets.show', ['petId' => $id])->with('success', $result['message']);
        }

        return redirect()->back()->withErrors(['error' => $result['message']]);
    }

    /**
     * Display the specified pet.
     */
    public function show($petId)
    {
        try {
            $data = $this->petService->getPet($petId);
        } catch (\Exception $e) {
            return view('error', ['exception' => $e]);
        }

        return view('detail', compact('data'));
    }

    public function upload($petId)
    {
        try {
            $pet = $this->petService->getPet($petId);
        } catch (\Exception $e) {
            return view('error', ['exception' => $e]);
        }

        return view('uploadImages', ['pet' => $pet]);
    }

    public function uploadImage(Request $request, int $petId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'additionalMetadata' => 'nullable|string',
        ]);

        $result = $this->petService->uploadPetImage(
            $petId,
            $request->file('file'),
            $request->input('additionalMetadata')
        );

        if ($result['status'] === 'success') {
            return redirect()->route('pets.show', ['petId' => $petId])->with('success', $result['message']);
        }

        return redirect()->back()->withErrors(['error' => $result['message']]);
    }

    public function deletePet(int $petId)
    {
        $result = $this->petService->deletePet($petId);

        if ($result['status'] === 'success') {
            return redirect()->back()->withErrors(['success' => $result['message']]);
        }

        return redirect()->back()->withErrors(['error' => $result['message']]);
    }
}
