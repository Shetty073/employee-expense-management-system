<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'amount',
        'approved_amount',
        'remark',
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

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
