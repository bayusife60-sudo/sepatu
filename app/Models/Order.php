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
        'customer_phone',
        'service_method',
        'latitude',
        'longitude',
        'distance',
        'pickup_address',
        'pickup_date',
        'pickup_time',
        'delivery_address',
        'delivery_date',
        'pickup_fee',
        'delivery_fee',
        'total_price',
        'status',
        'payment_status',
        'payment_proof',
        'payment_method',
        'payment_date',
        'estimated_completion',
        'xendit_invoice_id',
        'xendit_external_id',
        'payment_link',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class , 'customer_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
