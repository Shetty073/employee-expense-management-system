<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'description',
        'bill',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'double',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function expensecategory()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
