<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolderDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'type',
        'label',
        'name'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
