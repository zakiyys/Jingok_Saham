<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatchlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticker' => ['required', 'string', 'max:10', 'regex:/^[A-Z0-9]+$/'],
        ];
    }
}
