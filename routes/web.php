<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;

// Pantalla única: selector de país y resultados.
Route::get('/', [CountryController::class, 'index'])
    ->name('countries.index');

// Datos de ciudades de un país (consumido por AJAX desde la pantalla).
Route::get('/paises/{code}/ciudades', [CityController::class, 'byCountry'])
    ->where('code', '[A-Za-z]{3}')
    ->name('cities.by-country');
