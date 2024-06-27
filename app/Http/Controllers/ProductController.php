<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColorSizeRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use \App\Http\Traits\product;

    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => ProductResource::collection($products)]);
    }

    public function store(StoreColorSizeRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            return $this->storeRequest($request, $product->id ? $product : null);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
        }
    }


    public function show(Product $product)
    {
        return response()->json(['product' => ProductResource::collection($product)]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['name' => 'required|max:255 |string']);
        $products = $this->executeSearch($validated['name']);
        if ($products->isNotEmpty()) {
            return response()->json(['products' => ProductResource::collection($products)]);
        }
        return response()->json(['message' => 'لا توجد منتجات بهذ الاسم']);
    }


}
