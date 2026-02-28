<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_name', 
        'amount', 
        'status', 
        'quantity', 
        'customer_name', 
        'user_id'
    ];
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}