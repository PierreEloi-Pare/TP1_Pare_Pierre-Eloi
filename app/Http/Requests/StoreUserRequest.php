<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstName' => 'required',
            'lastName' => 'required',
            'email'=> 'required|unique:users,email',
            'phone' => 'required|unique:users,email'
        ];
    }
}
