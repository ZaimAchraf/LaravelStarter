<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'comment',
        'document',
        'credit_id',
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
