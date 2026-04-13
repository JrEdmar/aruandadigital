<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Contracts\View\View as ViewContract;

class ShopController extends Controller
{
    public function index(): View
    {
        $products = Product::active()->paginate(16);
        return view('shop.index', compact('products'));
    }

    public function show(string $id): View
    {
        $product = Product::active()->findOrFail($id);
        return view('shop.show', compact('product'));
    }

    public function cart(): View
    {
        $cart      = session('cart', []);
        $cartItems = [];
        $total     = 0;

        foreach ($cart as $id => $item) {
            $cartItems[] = $item;
            $total += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        }

        return view('shop.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::active()->findOrFail($request->product_id);
        $qty     = max(1, (int) ($request->quantity ?? 1));

        $cart = session('cart', []);
        $id   = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'qty'   => $qty,
                'image' => $product->first_image_url,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count'   => array_sum(array_column($cart, 'qty')),
            'message' => 'Produto adicionado ao carrinho.',
        ]);
    }

    public function removeFromCart(Request $request, string $id): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produto removido do carrinho.');
    }

    public function checkout(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Seu carrinho está vazio.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        }

        return view('shop.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Seu carrinho está vazio.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:pix,card,boleto'],
        ]);

        // Revalida estoque e preços atuais dos produtos antes de fechar o pedido
        $productIds      = array_column($cart, 'id');
        $freshProducts   = Product::active()->whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cart as $item) {
            $product = $freshProducts->get($item['id']);
            if (! $product) {
                return back()->with('error', 'O produto "' . $item['name'] . '" não está mais disponível.');
            }
            if ($product->stock !== null && $product->stock < $item['qty']) {
                return back()->with('error', 'Estoque insuficiente para "' . $product->name . '". Disponível: ' . $product->stock . '.');
            }
        }

        // Recalcula total com preços atuais do banco (evita manipulação de sessão)
        $total = collect($cart)->sum(fn ($i) => ($freshProducts->get($i['id'])?->price ?? 0) * ($i['qty'] ?? 1));

        $order = DB::transaction(function () use ($cart, $total, $request, $freshProducts) {
            $order = Order::create([
                'user_id'        => Auth::id(),
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'status'         => 'pending',
            ]);

            foreach ($cart as $item) {
                $product = $freshProducts->get($item['id']);
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unit_price' => $product->price,
                ]);
                // Decrementa estoque
                if ($product->stock !== null) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders')->with('success', 'Pedido realizado com sucesso! Número: #' . $order->id);
    }
}
