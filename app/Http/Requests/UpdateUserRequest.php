<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $currentUser = $this->user();
        $targetUser = $this->route('user');

        if (!$currentUser) {
            return false;
        }

        return $currentUser->can('manage-users') || ($targetUser && $currentUser->is($targetUser));
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$userId}"],
            'username' => ['required', 'string', "unique:users,username,{$userId}", 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'picture' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }
}
