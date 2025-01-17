<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PetController;


Route::get('/{status?}', [PetController::class, 'index'])
    ->defaults('status', 'available')
    ->where('status', implode('|', array_column(\App\Enums\Status::cases(), 'value')))
    ->name('pets.index');


Route::prefix('/pet')->group(function () {
    Route::get('/{petId}/uploadImage', [PetController::class, 'upload'])->name('pets.images');
    Route::post('/{petId}/uploadImage', [PetController::class, 'uploadImage'])->name('pets.uploadImage');
    Route::get('/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/store', [PetController::class, 'store'])->name('pets.store');

    Route::get('/edit/{petId}', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/update/{petId}', [PetController::class, 'update'])->name('pets.update');

    Route::get('/detail/{petId}', [PetController::class, 'show'])->name('pets.show');

    Route::delete('/delete/{petId}', [PetController::class, 'deletePet'])->name('pets.delete');
});
