<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedDoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
