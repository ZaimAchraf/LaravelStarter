<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'total',
        'title',
        'type',
        'is_active',
        'accord_id'
    ];



    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function accord()
    {
        return $this->belongsTo(Quotation::class, 'accord_id', 'id');
    }


    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'accord_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function quotationLines()
    {
        return $this->hasMany(QuotationLine::class);
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
