<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'admin_id',
        'old_price',
        'new_price',
        'difference',
        'reason',
        'status',
        'rejection_note',
    ];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class , 'admin_id');
    }
}
