<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AveragePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
         return [
            'minDate' => 'nullable|date',
            'maxDate' => 'nullable|date|after_or_equal:minDate', //https://laravel.com/docs/12.x/validation#rule-after-or-equal
            'maxTotalPrice' => 'nullable|numeric|min:0'
        ];
    }
}
