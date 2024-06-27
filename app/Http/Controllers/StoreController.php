<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColorSizeRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class StoreController extends Controller
{
    public function index()
    {
        $store = Store::all();
        return response()->json(['store' => StoreResource::collection($store)]);
    }

    public function store(StoreColorSizeRequest $request, Store $store)
    {
        DB::beginTransaction();
        try {
            return $this->storeRequest($request, $store->id ? $store : null);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    private function storeRequest($request, $store)
    {
        if (!$store) {
            $store = $this->storeProduct($request);
        }
        foreach ($request['sizes_id'] as $key => $sizeId) {
            if ($this->storeSize($store, $sizeId)) {
                return $this->storeSize($store, $sizeId);
            }
            foreach ($request->$key as $colorRequest) {
                if ($this->storeColor($store, $colorRequest, $sizeId)) {
                    return $this->storeColor($store, $colorRequest, $sizeId);
                }
            }
        }
        DB::commit();
        return response()->json(['message' => 'store created successfully', 'store' => new StoreResource($store)]);
    }

    private function storeProduct($request)
    {
        $validated = $request->validate(['product_id' => 'required|numeric|exists:products,id']);
        $storeExist = Store::where('product_id', $validated['product_id'])->first();
        if (!$storeExist) {
            return Store::create($validated);
        }
        return $storeExist;
    }

    private function storeSize($store, $sizeId)
    {
        $size = $store->product->sizes()->wherePivot('size_id', $sizeId)->first();
        if ($size) {
            if ($size->pivot->store_id === null) {
                $size->pivot->store_id = $store->id;
                $size->pivot->save();
            }
            return null;
        }
        return response()->json(['message' => "Size Id Not found this Product " . $store->product->name]);
    }

    private function storeColor($store, $colorRequest, $sizeId)
    {
        if (valColor($colorRequest)) {
            return valColor($colorRequest);
        }
        $size = $store->product->sizes()->wherePivot('size_id', $sizeId)->first();
        $color = $size->colorsProduct()->wherePivot('product_id', $store->product->id)
            ->wherePivot('color_id', $colorRequest['colorId'])->first();
        return $this->checkColor($store, $size, $color, $colorRequest);
    }

    private function checkColor($store, $size, $color, $colorRequest)
    {
        if (!$color) {
            return response()->json(['message' => "Color Id Not found this Size " . $size->name]);
        }
        if ($colorRequest['quantity'] > $color->pivot->product_quantity) {
            return response()->json(['message' => 'quantity Not  Available']);
        }
        if ($color->pivot->store_id === null) {
            $color->pivot->store_id = $store->id;
            $color->pivot->store_quantity = $colorRequest['quantity'];
        } else {
            $color->pivot->store_quantity += $colorRequest['quantity'];
        }
        $color->pivot->product_quantity -= $colorRequest['quantity'];
        $color->pivot->save();
        return null;
    }

    public function show(Store $store)
    {
        return response()->json(['store' => new StoreResource($store)]);
    }

    /* Not Completed*/
    public function restore(StoreColorSizeRequest $request, Store $store)
    {
        DB::beginTransaction();
        try {
            return $this->restoreSize($request, $store);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()]);
        }

    }

    private function restoreSize($request, $store)
    {
        foreach ($request['sizes_id'] as $key => $sizeId) {
            $size = $store->sizes()->wherePivot('size_id', $sizeId)->first();
            if ($size) {
                return $this->restoreColor($request, $key, $store, $size);
            } else {
                return response()->json(['message' => 'Size Not  Available for This Store ' . $size->name]);
            }
        }
        return null;
    }

    private function restoreColor($request, $key, $store, $size)
    {
        foreach ($request->$key as $colorRequest) {
            if (valColor($colorRequest)) {
                return valColor($colorRequest);
            }
            $color = $size->colorsStore()->wherePivot('store_id', $store->id)->wherePivot('color_id', $colorRequest['colorId'])->first();
            if ($color) {
                if ($colorRequest['quantity'] > $color->pivot->store_quantity) {
                    return response()->json(['message' => 'Refund quantity Not  Available']);
                }
                $color->pivot->product_quantity += $colorRequest['quantity'];
                $color->pivot->store_quantity -= $colorRequest['quantity'];
                if ($color->pivot->store_quantity === 0) {
                    $color->pivot->store_id = null;
                }
                $color->pivot->save();
            } else {
                return response()->json(['message' => 'اللون غير متوفر لهذا المقاس']);
            }
        }
        $this->checkColorSize($size, $store);
        DB::commit();
        return response()->json(['message' => 'تم الاسترجاع بنجاح']);
    }

    private function checkColorSize($size, $store)
    {
        $colorExist = $size->colorsStore()->wherePivot('store_id', $store->id)->get();
        if ($colorExist->isEmpty()) {
            $size->pivot->store_id = null;
            $size->pivot->save();
            if ($store->sizes->isEmpty()) {
                $store->delete();
            }
        }
        return null;
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['name' => 'required|max:255 |string']);
        $products = $this->executeSearch($validated['name']);
        if ($products->isNotEmpty()) {
            return response()->json(['stores' => ProductResource::collection($products)]);
        }
        return response()->json(['message' => 'لا توجد منتجات بهذ الاسم']);
    }

    private function executeSearch($search)
    {
        return Store::with('product')
            ->select('id', 'name', 'price')->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->get();
    }
}
