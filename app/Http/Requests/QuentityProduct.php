<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuentityProduct extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
 // quantity , product_id , color_id ,Size_id
    public function rules(): array
    {
        return [
            //'product_id' => ['required','numeric','exists:products,id'],
            //'color_id' => ['required','numeric','exists:colors,id'],
            //'size_id' => ['required','numeric','exists:sizes,id'],


        ];
    }
}
