<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
    ];

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'voucher_job');
    }
}
