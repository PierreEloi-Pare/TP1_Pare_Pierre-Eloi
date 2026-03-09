<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    //https://laravel.com/docs/12.x/validation
    public function rules(): array
    {
        return [
            'firsName' => 'required',
            'lasName' => 'required',
            'email'=> 'required|unique:user,email',
            'phone' => 'required|unique:user,phone'
        ];
    }
}