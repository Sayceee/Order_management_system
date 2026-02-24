<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // GET all orders - like index() in your PDF
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
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }
        
        return response()->json($order);
    }
    
    // POST create new order - like store() in your PDF
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'user_id' => 'required|string',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0'
        ]);
        
        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);
        
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }
    
    // PUT update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid'
        ]);
        
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }
        
        $order->status = $request->status;
        $order->save();
        
        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}