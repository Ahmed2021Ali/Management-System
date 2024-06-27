<?php

namespace App\Http\Traits;

use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

trait product
{
    private function storeRequest($request, $product)
    {
        if (!$product) {
            $product = $this->storeProduct($request);
        }
        foreach ($request['sizes_id'] as $key => $sizeId) {
            $this->storeSize($product, $sizeId);
            foreach ($request->$key as $colorRequest) {
                if ($this->storeColor($product, $colorRequest, $sizeId)) {
                    return $this->storeColor($product, $colorRequest, $sizeId);
                }
            }
        }
        DB::commit();
        return response()->json(['message' => 'product created successfully', 'product' => new ProductResource($product)]);
    }

    private function storeProduct($request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:256|min:2', 'price' => 'required|numeric|max:999999|min:1',
            'code' => 'required|max:256|min:2', 'total_quantity' => 'required|numeric|max:999999|min:1',
        ]);
        return \App\Models\Product::create($validatedData);
    }

    private function storeSize($product, $sizeId)
    {
        $size = $product->sizes()->wherePivot('size_id', $sizeId)->first();
        if (!$size) {
            $product->sizes()->attach($sizeId);
        }
    }

    private function storeColor($product, $colorRequest, $sizeId)
    {
        if (valColor($colorRequest)) {
            return valColor($colorRequest);
        }
        $size = $product->sizes()->wherePivot('size_id', $sizeId)->first();
        $color = $size->colorsProduct()->wherePivot('product_id', $product->id)
            ->wherePivot('color_id', $colorRequest['colorId'])->first();
        return $this->checkColor($size, $color, $product, $colorRequest);
    }

    private function checkColor($size, $color, $product, $colorRequest)
    {
        if (!$color) {
            $size->colorsProduct()->attach($colorRequest['colorId'], [
                'product_id' => $product->id, 'product_quantity' => $colorRequest['quantity']]);
        } else {
            $color->pivot->product_quantity += $colorRequest['quantity'];
            $color->pivot->save();
        }
        return null;
    }

    private function executeSearch($search)
    {
        return \App\Models\Product::select('id', 'name', 'price')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->get();
    }

}
