<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Contracts\View\View;

class CountryController extends Controller
{
    /**
     * Muestra la única pantalla de la aplicación con el selector de países.
     */
    public function index(): View
    {
        $countries = Country::query()
            ->select(['Code', 'Name'])
            ->orderBy('Name')
            ->get();

        return view('countries.index', compact('countries'));
    }
}
