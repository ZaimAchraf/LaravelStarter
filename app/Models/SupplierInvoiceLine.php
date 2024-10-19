<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'c_product_id',
        'invoice_id',
        'Qte',
        'price'
    ];

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(CProduct::class);
    }
}
