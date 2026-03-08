<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    public function storeUserPicture(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/users';
        
        // Create directory if it doesn't exist
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }
        
        $file->move(public_path($path), $filename);
        return $filename;
    }

    public function deleteUserPicture(string $path): bool
    {
        $fullPath = public_path('uploads/users/' . $path);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}