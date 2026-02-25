<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'merchant_request_id',
        'checkout_request_id',
        'mpesa_receipt',
        'amount',
        'phone',
        'status',
        'result_code',
        'result_desc',
        'raw_callback',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_callback' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}