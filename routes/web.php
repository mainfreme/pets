<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PetController;


Route::get('/{status?}', [PetController::class, 'index'])
    ->defaults('status', 'available')
    ->where('status', implode('|', array_column(\App\Enums\Status::cases(), 'value')))
    ->name('pets.index');
