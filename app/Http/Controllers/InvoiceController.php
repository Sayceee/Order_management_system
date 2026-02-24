<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    // Generate PDF invoice
    public function generateInvoice($orderId)
    {
        try {
            // Validate order ID
            if (!is_numeric($orderId)) {
                return response()->json(['error' => 'Invalid order ID'], 400);
            }
            
            // Fetch order data from Person 1's API
            // CHANGE THIS URL to match Person 1's server
            $response = Http::timeout(5)->get('http://localhost:3000/orders/' . $orderId);
            
            if ($response->failed()) {
                if ($response->status() == 404) {
                    return response()->json(['error' => 'Order not found'], 404);
                }
                return response()->json(['error' => 'Order service unavailable'], 503);
            }
            
            $order = $response->json();
            
            // Verify order has required fields
            if (!isset($order['id'], $order['product_name'], $order['amount'])) {
                return response()->json(['error' => 'Invalid order data'], 500);
            }
            
            // Generate QR Code
            $trackingUrl = url('/track/' . $orderId);
            $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($trackingUrl));
            
            // Prepare data for PDF
            $data = [
                'order' => $order,
                'qrCode' => $qrCode,
                'company' => [
                    'name' => 'YOUR STORE NAME',
                    'address' => '123 Business Street, Nairobi',
                    'phone' => '0712 345 678',
                    'email' => 'info@yourstore.com'
                ]
            ];
            
            // Generate PDF
            $pdf = Pdf::loadView('pdf.invoice', $data);
            
            // Return PDF for download
            return $pdf->download('invoice-' . $orderId . '.pdf');
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('PDF generation failed - connection error: ' . $e->getMessage());
            return response()->json(['error' => 'Cannot connect to order service'], 503);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
    
    // Tracking page
    public function trackOrder($orderId)
    {
        try {
            // Validate order ID
            if (!is_numeric($orderId)) {
                return view('tracking.not-found');
            }
            
            // Fetch order from Person 1's API
            // CHANGE THIS URL to match Person 1's server
            $response = Http::timeout(5)->get('http://localhost:3000/orders/' . $orderId);
            
            if (!$response->successful()) {
                return view('tracking.not-found');
            }
            
            $order = $response->json();
            
            return view('tracking.show', ['order' => $order]);
            
        } catch (\Exception $e) {
            Log::error('Tracking page failed: ' . $e->getMessage());
            return view('tracking.not-found');
        }
    }
}