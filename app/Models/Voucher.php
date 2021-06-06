<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'number',
        'status',
        'approved_amount',
        'approval_date',
        'special_remark',
        'payment_mode_draft',
        'payment_date_draft',
        'payment_remark_draft',
    ];

    protected $casts = [
        'approved_amount' => 'double',
        'approval_date' => 'date',
        'date' => 'date',
        'payment_date_draft' => 'date',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function addprovedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'voucher_job');
    }

    public function submitteddocs()
    {
        return $this->hasMany(SubmittedDoc::class);
    }

    public function returnablelistdocs()
    {
        return $this->hasMany(ReturnableListDoc::class);
    }

    public function receiveddocs()
    {
        return $this->hasMany(ReceivedDoc::class);
    }

    public function sitecompletiondocs()
    {
        return $this->hasMany(SiteCompletionDoc::class);
    }
}
