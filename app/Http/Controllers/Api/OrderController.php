<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Http\Controllers\MpesaController;
use Illuminate\Support\Facades\Log;

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
    
    // POST create new order
    public function store(Request $request)
    {
        try {
            // Validate
            $request->validate([
                'product_name' => 'required|string',
                'amount' => 'required|numeric',
                'phone' => 'required|string',
                'customer_name' => 'nullable|string'
            ]);

            // Create order
            $order = Order::create([
                'customer_name' => $request->customer_name ?? auth()->user()->name ?? 'Guest',
                'product_name' => $request->product_name,
                'quantity' => $request->quantity ?? 1,
                'amount' => $request->amount * ($request->quantity ?? 1),
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            Log::info('Order created: ' . $order->id);

            // Call M-Pesa
            $mpesaController = new MpesaController();
            return $mpesaController->stkPush(new Request([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'phone' => $request->phone,
            ]));
            
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }
    
    // PUT update order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,paid,failed'
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