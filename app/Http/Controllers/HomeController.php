<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\House;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $events = Event::with('house')
            ->where('visibility', 'public')
            ->whereIn('status', ['open', 'full'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->take(8)
            ->get();

        $houses = House::where('status', 'active')
            ->orderBy('name')
            ->take(8)
            ->get();

        // LOJA DESABILITADA — descomentar para reativar
        // $products = Product::active()->latest()->take(6)->get();
        $products = collect();

        $featured = $events->first();

        // Eventos de hoje da casa do usuário (lembrete de gira)
        $userHouse = $user->houses()->wherePivot('status', 'active')->first();
        $todayEvents = $userHouse
            ? Event::where('house_id', $userHouse->id)
                ->whereDate('starts_at', today())
                ->whereNotIn('status', ['cancelled', 'draft'])
                ->get()
            : collect();

        return view('home', compact('user', 'events', 'houses', 'products', 'featured', 'todayEvents'));
    }
}
