<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'title',
        'total',
        'document',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryNoteLines()
    {
        return $this->hasMany(DeliveryNoteLine::class);
    }
}
