<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
