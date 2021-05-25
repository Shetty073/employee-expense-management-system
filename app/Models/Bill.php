<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
