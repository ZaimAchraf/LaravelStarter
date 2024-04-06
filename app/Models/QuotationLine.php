<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'state',
        'price',
        'quantity',
        'quotation_id'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
