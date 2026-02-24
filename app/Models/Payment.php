<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
>>>>>>> 7d36540 (Initial commit)
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
<<<<<<< HEAD
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'transaction_id',
        'status'
    ];
    
    // A payment belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
=======
    /**
     * The table associated with the model.
     *
     * Laravel will pluralize the class name by default, but it's
     * good form to declare it explicitly in case the convention
     * ever changes or the model is renamed.
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * Define the columns that you expect to insert/update so that
     * `Payment::create()` and `fill()` calls work without errors.
     */
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

    /**
     * Casts for convenience when working with JSON/decimal fields.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'raw_callback' => 'array',
    ];
}
>>>>>>> 7d36540 (Initial commit)
