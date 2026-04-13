<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\View\View;

class MapController extends Controller
{
    /** Exibe o mapa de casas/templos próximos. */
    public function index(): View
    {
        $houses = House::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'type', 'city', 'state', 'latitude', 'longitude', 'logo_image']);

        return view('map.index', compact('houses'));
    }
}
