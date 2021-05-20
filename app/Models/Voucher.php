<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'status',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'voucher_job');
    }
}
