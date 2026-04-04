<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'expense_category_id',
        'description',
        'amount',
        'payment_method',
        'proof_of_payment',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class , 'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
