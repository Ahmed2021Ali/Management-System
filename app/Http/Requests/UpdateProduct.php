<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sizes_id' => ['array', 'required'],
            'sizes_id.*' => ['required', 'numeric', 'exists:sizes,id'],
        ];
    }
}
