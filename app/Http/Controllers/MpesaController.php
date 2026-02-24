<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MpesaController extends Controller
{
    // Daraja base URL (Sandbox)
    private function baseUrl(): string
    {
        return 'https://sandbox.safaricom.co.ke';
    }

    // Get OAuth token
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

    // POST /api/mpesa/stkpush
    public function stkPush(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required'],
            'phone' => ['required', 'string'],     // 2547XXXXXXXX
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $timestamp = now()->format('YmdHis');
        $shortcode = env('MPESA_SHORTCODE');
        $passkey = env('MPESA_PASSKEY');

        $password = base64_encode($shortcode . $passkey . $timestamp);

        $payload = [
            "BusinessShortCode" => $shortcode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => (int)$data['amount'],
            "PartyA" => $data['phone'],
            "PartyB" => $shortcode,
            "PhoneNumber" => $data['phone'],
            "CallBackURL" => env('MPESA_CALLBACK_URL'),
            "AccountReference" => "ORDER-" . $data['order_id'],
            "TransactionDesc" => "Order Payment",
        ];

        $res = Http::withoutVerifying()
            ->withToken($this->accessToken())
            ->acceptJson()
            ->post($this->baseUrl() . '/mpesa/stkpush/v1/processrequest', $payload);

        // record initial request in payments table when Daraja accepts the push
        $response = $res->json();
        if (($response['ResponseCode'] ?? null) === "0") {
            \App\Models\Payment::create([
                'order_id' => (int) $data['order_id'],
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
                'checkout_request_id' => $response['CheckoutRequestID'],
                'amount' => (float) $data['amount'],
                'phone' => $data['phone'],
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'daraja_status' => $res->status(),
            'daraja_response' => $response,
        ], $res->ok() ? 200 : 400);
    }

    // POST /api/mpesa/callback
    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::info('MPESA_CALLBACK_RAW', $payload);

        $stk = data_get($payload, 'Body.stkCallback');
        $resultCode = data_get($stk, 'ResultCode');
        $resultDesc = data_get($stk, 'ResultDesc');
        $checkoutId  = data_get($stk, 'CheckoutRequestID');
        $merchantId  = data_get($stk, 'MerchantRequestID');

        // CallbackMetadata Items -> key/value pairs
        $items = collect(data_get($stk, 'CallbackMetadata.Item', []))
            ->mapWithKeys(fn($i) => [data_get($i, 'Name') => data_get($i, 'Value')]);

        $amount   = $items->get('Amount');
        $receipt  = $items->get('MpesaReceiptNumber');
        $phone    = $items->get('PhoneNumber');
        $trxDate  = $items->get('TransactionDate');

        // update the payment record that was created during stkPush
        \App\Models\Payment::where('checkout_request_id', $checkoutId)
            ->update([
                'status' => ((int) $resultCode === 0) ? 'paid' : 'failed',
                'result_code' => (int) $resultCode,
                'result_desc' => $resultDesc,
                'mpesa_receipt' => $receipt,
                'amount' => $amount,
                'phone' => $phone,
                'raw_callback' => $payload,
            ]);

        Log::info('MPESA_CALLBACK_PARSED', [
            'resultCode' => $resultCode,
            'resultDesc' => $resultDesc,
            'checkoutId' => $checkoutId,
            'merchantId' => $merchantId,
            'amount' => $amount,
            'receipt' => $receipt,
            'phone' => $phone,
            'trxDate' => $trxDate,
        ]);

        // simple success / failure handling
        if ((int) $resultCode === 0) {
            // the payment went through – mark order as paid via checkoutId lookup
            Log::info('PAYMENT_SUCCESS', [
                'checkoutId' => $checkoutId,
                'receipt'    => $receipt,
                'amount'     => $amount,
                'phone'      => $phone,
            ]);
        } else {
            // any non‑zero code means the push failed, was cancelled or timed out
            Log::warning('PAYMENT_FAILED', [
                'checkoutId'  => $checkoutId,
                'resultCode'  => $resultCode,
                'resultDesc'  => $resultDesc,
            ]);
        }

        // OPTIONAL: If you stored checkoutId when sending STK push,
        // you can now mark the order as paid using checkoutId -> order_id mapping.

        return response()->json([
            "ResultCode" => 0,
            "ResultDesc" => "Accepted"
        ]);
    }
}