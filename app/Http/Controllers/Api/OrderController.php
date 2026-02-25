<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\MpesaController; // Link to your M-Pesa logic

class OrderController extends Controller
{
    // GET all orders
    public function index()
    {
        $orders = Order::with('payment')->get();
        return response()->json($orders);
    }
    
    // GET single order
    public function show($id)
    {
        $order = Order::with('payment')->find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        return response()->json($order);
    }
    
    // POST create new order - AUTOMATED FOR M-PESA
    public function store(Request $request)
    {
        // 1. Validate data from your storefront
        $request->validate([
            'product_name' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|string', // Needed for the STK Push
        ]);
        
        // 2. Automatically save to the database
        $order = Order::create([
            'product_name' => $request->product_name,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);
        
        // 3. HAND-OFF TO M-PESA AUTOMATICALLY
        // This triggers the STK push using the ID we just created
        return app(MpesaController::class)->stkPush(new Request([
            'order_id' => $order->id,
            'amount'   => $order->amount,
            'phone'    => $request->phone,
        ]));
    }
    
    // PUT update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid'
        ]);
        
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        $order->status = $request->status;
        $order->save();
        
        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}