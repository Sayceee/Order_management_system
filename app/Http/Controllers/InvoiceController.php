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
            $order = Order::with('payment')->find($orderId);

            if (!$order) {
                return view('tracking.not-found');
            }

            $trackingUrl = url('/track/' . $orderId);
            $qrCode = base64_encode(QrCode::format('svg')->size(150)->generate($trackingUrl));

            $data = [
                'order' => $order,
                'qrCode' => $qrCode,
                'company' => [
                    'name' => 'Faith\'s Crochet Shop',
                    'address' => 'Nairobi, Kenya',
                    'phone' => '+254 700 000 000',
                    'email' => 'support@crochetmarket.co.ke'
                ]
            ];

            $pdf = Pdf::loadView('pdf.invoice', $data);
            return $pdf->download('Invoice_Order_' . $orderId . '.pdf');

        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    public function trackOrder($orderId)
    {
        $order = Order::with('payment')->find($orderId);
        if (!$order) {
            return view('tracking.not-found');
        }
        return view('tracking.show', ['order' => $order]);
    }
}