<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // This connects to Person 1's model
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    // Generate PDF invoice
    public function generateInvoice($orderId)
{
    
    try {
        // This line replaces the "Http::get" that was causing the error
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found. Use artisan tinker to create one!'], 404);
        }

        $trackingUrl = url('/track/' . $orderId);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($trackingUrl));

        $data = [
            'order' => $order,
            'qrCode' => $qrCode,
            'company' => [
                'name' => 'Faith\'s Order Management',
                'address' => 'Nairobi, Kenya',
                'phone' => '0712 345 678',
                'email' => 'support@orderflow.co.ke'
            ]
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->download('invoice-' . $orderId . '.pdf');

    } catch (\Exception $e) {
        Log::error('Invoice generation failed: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
    // Tracking page
    public function trackOrder($orderId)
    {
        try {
            // Pull order directly from the database
            $order = Order::find($orderId);
            
            if (!$order) {
                return view('tracking.not-found');
            }
            
            return view('tracking.show', ['order' => $order]);
            
        } catch (\Exception $e) {
            Log::error('Tracking page failed: ' . $e->getMessage());
            return view('tracking.not-found');
        }
    }
}