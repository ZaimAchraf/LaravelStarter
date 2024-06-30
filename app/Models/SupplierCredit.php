<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'title',
        'paid',
        'status',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function lines()
    {
        return $this->hasMany(SupplierCreditLine::class);
    }
}
