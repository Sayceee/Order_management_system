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
            // 1. Pull order directly from the database using Person 1's Model
            $order = Order::with('payment')->find($orderId);
            
            if (!$order) {
                return response()->json(['error' => 'Order not found in database'], 404);
            }

            // 2. Generate QR Code
            $trackingUrl = url('/track/' . $orderId);
            $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($trackingUrl));
            
            // 3. Prepare data (Converting model to array for the view)
            $data = [
                'order' => $order->toArray(),
                'qrCode' => $qrCode,
                'company' => [
                    'name' => 'Faith\'s Order Management',
                    'address' => 'Nairobi, Kenya',
                    'phone' => '0712 345 678',
                    'email' => 'support@orderflow.co.ke'
                ]
            ];
            
            // 4. Generate and Download PDF
            $pdf = Pdf::loadView('pdf.invoice', $data);
            return $pdf->download('invoice-' . $orderId . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
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
            
            return view('tracking.show', ['order' => $order->toArray()]);
            
        } catch (\Exception $e) {
            Log::error('Tracking page failed: ' . $e->getMessage());
            return view('tracking.not-found');
        }
    }
}