<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'Product' => new ProductResource($this->product),
            'quantity' => $this->quantity,
            'price' => $this->price->name,
            'color' => $this->color->name,
            'User Created Order' => $this->user->name,
        ];
    }
}
