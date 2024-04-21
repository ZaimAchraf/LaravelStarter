<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'total',
        'paid',
        'comment',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

}
