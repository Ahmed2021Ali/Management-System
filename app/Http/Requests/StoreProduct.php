<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:256','min:5'],
            'price' =>[ 'required','numeric','max:999999','min:1']
/*            'sizes_id' => ['array', 'required'],
            'sizes_id.*' => ['required', 'numeric', 'exists:sizes,id'],*/
        ];
    }
}
