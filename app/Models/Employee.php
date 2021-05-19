<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'number',
        'email',
        'password',
        'photo',
        'wallet_balance'
    ];

    protected $casts = [
        'wallet_balance' => 'double',
    ];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
