<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AggregatedInvoiceLines extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'reference',
        'state',
        'type',
        'price',
        'TVA',
        'quantity',
        'invoice_id'
    ];

    public function aggregatedInvoice()
    {
        return $this->belongsTo(AggregatedInvoice::class, 'invoice_id');
    }
}
