<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'TVA',
        'total',
        'document'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function lines()
    {
        return $this->hasMany(SupplierInvoiceLine::class, 'invoice_id');
    }
}
