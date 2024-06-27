<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrder extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'quantity'=>['required','numeric','min:1','max:15'],
            'price'=>['required','numeric','min:1','max:15'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'color_id' => ['required', 'numeric', 'exists:colors,id'],
            'size_id' => ['required', 'numeric', 'exists:sizes,id'],
        ];
    }
}
