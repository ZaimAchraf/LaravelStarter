<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('access-dashboard');
    }

    public function rules()
    {
        $userID = $this->route('user')->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userID),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($userID),
            ],
            'phone'    => ['string', 'min:10'],
            'adresse'  => ['nullable','string', 'max:255'],
            'gendre'   => ['required', 'string', 'max:1'],
            'role_id'  => ['required', 'integer', 'exists:roles,id'],
            'images'    => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'salaire'  => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
