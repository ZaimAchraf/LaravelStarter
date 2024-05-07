<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'registration',
        'insurance',
        'chassis_number',
        'mileage',
        'police_number',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
