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
            'minDate' => 'nullable|date_format:Y-m-d', //https://stackoverflow.com/questions/50287823/validating-a-custom-date-format-in-with-laravel-validator
            'maxDate' => 'nullable|date_format:Y-m-d|after:minDate', //https://laravel.com/docs/12.x/validation#rule-after
            'maxTotalPrice' => 'nullable|numeric|min:0'
        ];
    }
}
