<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'total',
        'paid',
        'comment',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function lines()
    {
        return $this->hasMany(CreditLine::class);
    }

}
