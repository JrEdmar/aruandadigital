<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerController extends Controller
{
    /** Dashboard do vendedor com resumo de produtos e pedidos. */
    public function dashboard(): View
    {
        $user = Auth::user();

        $stats = [
            'total_products'  => Product::where('store_id', $user->id)->count(),
            'total_sales'     => Order::whereHas('orderItems.product', fn($q) => $q->where('store_id', $user->id))->whereIn('status', ['paid','shipped','delivered'])->count(),
            'pending_orders'  => Order::whereHas('orderItems.product', fn($q) => $q->where('store_id', $user->id))->where('status', 'pending')->count(),
            'revenue'         => Order::whereHas('orderItems.product', fn($q) => $q->where('store_id', $user->id))->whereIn('status', ['paid','shipped','delivered'])->sum('total'),
        ];

        $products = Product::where('store_id', $user->id)->latest()->paginate(12);

        return view('seller.dashboard', compact('user', 'stats', 'products'));
    }

    /** Área de atacado — somente loja_master. */
    public function wholesale(): View
    {
        $user     = Auth::user();
        $products = Product::where('store_id', $user->id)
            ->wholesale()
            ->paginate(12);

        return view('seller.wholesale', compact('user', 'products'));
    }

    /** Formulário de cadastro de produto. */
    public function createProduct(): View
    {
        return view('seller.product-create');
    }

    /** Salva novo produto. */
    public function storeProduct(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price'       => ['required', 'numeric', 'min:0.01'],
            'stock'       => ['nullable', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:100'],
        ]);

        Product::create([
            'store_id'    => Auth::id(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'stock'       => $data['stock'] ?? 0,
            'category'    => $data['category'] ?? null,
            'status'      => 'active',
        ]);

        return redirect()->route('seller')->with('success', 'Produto cadastrado com sucesso!');
    }
}
