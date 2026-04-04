<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'qr_code',
        'customer_id',
        'customer_name',
        'shoe_type',
        'shoe_brand',
        'treatment_id',
        'photo_before',
        'photo_after',
        'service_method',
        'pickup_address',
        'pickup_date',
        'delivery_address',
        'delivery_date',
        'pickup_fee',
        'delivery_fee',
        'price',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_date',
        'estimated_completion',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class , 'customer_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
