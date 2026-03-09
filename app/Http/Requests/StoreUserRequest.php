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
            'firsName' => 'required',
            'lasName' => 'required',
            'email'=> 'required|unique:user,email',
            'phone' => 'required|unique:user,email'
        ];
    }
}
