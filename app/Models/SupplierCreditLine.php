<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierCreditLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'comment',
        'document',
        'supplier_credit_id',
    ];

    public function credit()
    {
        return $this->belongsTo(SupplierCredit::class);
    }
}
