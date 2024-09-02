<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'vehicle_id',
        'title'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function documents()
    {
        return $this->hasMany(FolderDocument::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function credit()
    {
        return $this->hasOne(Credit::class);
    }
}
