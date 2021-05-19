<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'payment_mode',
        'amount',
        'remark',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'double',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
