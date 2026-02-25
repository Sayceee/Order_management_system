<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function generateInvoice($orderId)
    {
        try {
            // Ensure the order exists. Use Order #101 if testing manually.
            $order = Order::find($orderId);

            if (!$order) {
    
    return view('tracking.not-found', ['order_id' => $orderId]);
}

            $trackingUrl = url('/track/' . $orderId);
            // Size increased to 150 for better scanning on the PDF
            $qrCode = base64_encode(QrCode::format('svg')->size(150)->generate($trackingUrl));

            $data = [
                'order' => $order,
                'qrCode' => $qrCode,
                'company' => [
                    'name' => 'Faith\'s Crochet Shop',
                    'address' => 'Nairobi, Kenya',
                    'phone' => '0712 345 678',
                    'email' => 'sales@faithscrochet.co.ke'
                ]
            ];

            $pdf = Pdf::loadView('pdf.invoice', $data);
            
            // This forces the browser to download the file instantly
            return $pdf->download('Invoice_Order_' . $orderId . '.pdf');

        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function trackOrder($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return view('tracking.not-found');
        }
        return view('tracking.show', ['order' => $order]);
    }
}