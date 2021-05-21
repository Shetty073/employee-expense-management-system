<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
