<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactory extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'=>['required','string','min:2','max:250'],
            'price' => ['required', 'numeric', 'max:999999', 'min:1'],
        ];
    }
}
