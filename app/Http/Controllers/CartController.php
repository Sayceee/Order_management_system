<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        return view('cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1'
        ]);

        $existing = Cart::where('user_id', auth()->id())
            ->where('product_name', $request->product_name)
            ->first();

        if ($existing) {
            $existing->quantity += $request->quantity;
            $existing->save();
            $message = 'Quantity updated in cart!';
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_name' => $request->product_name,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);
            $message = 'Item added to cart!';
        }

        return redirect()->route('shop')->with('success', $message);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json(['success' => true]);
    }

    public function remove($id)
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    public function checkout(Request $request)
{
    try {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Check if user has phone number
        if (!auth()->user()->phone) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please add your phone number to your profile first.');
        }

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Create order with all items
        $itemsList = $cartItems->map(function($item) {
            return $item->quantity . 'x ' . $item->product_name;
        })->implode(', ');
        
        $order = Order::create([
            'customer_name' => auth()->user()->name,
            'product_name' => $itemsList,
            'quantity' => 1,
            'amount' => $total,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        // Clear cart
        Cart::where('user_id', auth()->id())->delete();

        // Trigger M-Pesa
        $mpesaController = new MpesaController();
        
        // Log before calling M-Pesa
        \Log::info('Calling M-Pesa for order: ' . $order->id . ', amount: ' . $total . ', phone: ' . auth()->user()->phone);
        
        return $mpesaController->stkPush(new Request([
            'order_id' => $order->id,
            'amount' => $total,
            'phone' => auth()->user()->phone,
        ]));
        
    } catch (\Exception $e) {
        \Log::error('Checkout error: ' . $e->getMessage());
        return redirect()->route('cart.index')->with('error', 'Checkout failed: ' . $e->getMessage());
    }
}
}