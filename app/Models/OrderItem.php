<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shoe_brand',
        'shoe_material',
        'shoe_color',
        'treatment_id',
        'price',
        'photo_before',
        'photo_after',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
