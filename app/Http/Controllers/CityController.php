<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Devuelve en JSON todas las ciudades de un país, su Top 10 más poblado
     * y su Bottom 10 menos poblado.
     *
     * El campo `code` es obligatorio y debe existir en la tabla `country`.
     */
    public function byCountry(Request $request, string $code): JsonResponse
    {
        $request->merge(['code' => $code]);

        $validated = $request->validate([
            'code' => 'required|exists:country,Code',
        ]);

        $country = Country::query()->findOrFail($validated['code']);

        $cities = $country->cities()
            ->orderByDesc('Population')
            ->orderBy('Name')
            ->get(['ID', 'Name', 'District', 'Population']);

        return response()->json([
            'country' => [
                'code' => $country->Code,
                'name' => $country->Name,
            ],
            'total' => $cities->count(),
            'top10' => $cities->take(10)->values(),
            'bottom10' => $cities->sortBy('Population')->values()->take(10),
            'cities' => $cities->values(),
        ]);
    }
}
