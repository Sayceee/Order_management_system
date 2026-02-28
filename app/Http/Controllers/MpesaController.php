<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Payment;

class MpesaController extends Controller
{
    private function baseUrl(): string
    {
        return 'https://sandbox.safaricom.co.ke';
    }

    private function accessToken(): string
    {
        $res = Http::withoutVerifying()
            ->withBasicAuth(env('MPESA_CONSUMER_KEY'), env('MPESA_CONSUMER_SECRET'))
            ->get($this->baseUrl() . '/oauth/v1/generate?grant_type=client_credentials');

        if (!$res->ok() || empty($res['access_token'])) {
            throw new \Exception('Token error: ' . $res->body());
        }

        return $res['access_token'];
    }

    public function stkPush(Request $request)
{
    try {
        $data = $request->validate([
            'order_id' => 'required',
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        \Log::info('STK Push initiated', $data);

        $timestamp = now()->format('YmdHis');
        $shortcode = env('MPESA_SHORTCODE', '174379');
        $passkey = env('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919');
        $password = base64_encode($shortcode . $passkey . $timestamp);

        // Format phone number (remove 0 or +254)
        $phone = $data['phone'];
        $phone = preg_replace('/^0/', '254', $phone);
        $phone = preg_replace('/^\+/', '', $phone);

        $payload = [
            "BusinessShortCode" => $shortcode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => (int)$data['amount'],
            "PartyA" => $phone,
            "PartyB" => $shortcode,
            "PhoneNumber" => $phone,
            "CallBackURL" => env('MPESA_CALLBACK_URL', 'https://your-domain.com/mpesa/callback'),
            "AccountReference" => "ORDER-" . $data['order_id'],
            "TransactionDesc" => "Order Payment",
        ];

        \Log::info('M-Pesa Payload', $payload);

        $res = Http::withoutVerifying()
            ->withToken($this->accessToken())
            ->acceptJson()
            ->post($this->baseUrl() . '/mpesa/stkpush/v1/processrequest', $payload);

        $response = $res->json();
        \Log::info('M-Pesa Response', $response);
        
        if (($response['ResponseCode'] ?? null) === "0") {
            Payment::create([
                'order_id' => (int) $data['order_id'],
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
                'checkout_request_id' => $response['CheckoutRequestID'],
                'amount' => (float) $data['amount'],
                'phone' => $phone,
                'status' => 'pending',
            ]);

            return redirect()->route('order.success', ['order_id' => $data['order_id']])
                ->with('success', 'STK Push sent! Please check your phone.');
        }

        return redirect()->route('cart.index')
            ->with('error', 'M-Pesa request failed. Please try again.');
        
    } catch (\Exception $e) {
        \Log::error('STK Push failed: ' . $e->getMessage());
        return redirect()->route('cart.index')
            ->with('error', 'Payment failed: ' . $e->getMessage());
    }
}

    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('MPESA_CALLBACK', $payload);

        $stk = data_get($payload, 'Body.stkCallback');
        $resultCode = data_get($stk, 'ResultCode');
        $resultDesc = data_get($stk, 'ResultDesc');
        $checkoutId = data_get($stk, 'CheckoutRequestID');

        $items = collect(data_get($stk, 'CallbackMetadata.Item', []))
            ->mapWithKeys(fn($i) => [data_get($i, 'Name') => data_get($i, 'Value')]);

        $amount = $items->get('Amount');
        $receipt = $items->get('MpesaReceiptNumber');
        $phone = $items->get('PhoneNumber');

        $payment = Payment::where('checkout_request_id', $checkoutId)->first();
        
        if ($payment) {
            $payment->update([
                'status' => ((int) $resultCode === 0) ? 'paid' : 'failed',
                'result_code' => (int) $resultCode,
                'result_desc' => $resultDesc,
                'mpesa_receipt' => $receipt,
                'amount' => $amount ?? $payment->amount,
                'phone' => $phone ?? $payment->phone,
                'raw_callback' => $payload,
            ]);

            if ((int) $resultCode === 0) {
                $order = Order::find($payment->order_id);
                if ($order) {
                    $order->update(['status' => 'paid']);
                    Log::info('Order ' . $order->id . ' marked as paid');
                }
            }
        }

        return response()->json(["ResultCode" => 0, "ResultDesc" => "Accepted"]);
    }
}