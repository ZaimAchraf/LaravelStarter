<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCreditRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('access-dashboard');
    }

    public function rules()
    {
        return [
            'paid'     => ['required', 'numeric'],
            'comment' => [
                'required',
                'string',
                'max:255'
            ]
        ];
    }
}
