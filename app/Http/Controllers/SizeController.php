<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSize;
use App\Http\Requests\UpdateSize;
use App\Http\Resources\SizeProductResource;
use App\Http\Resources\SizeResource;
use App\Models\Size;

class SizeController extends Controller
{
    /*  Show All Size  */
    public function index()
    {
        $sizes = Size::select('id', 'name')->get();
        return response()->json([$sizes]);
    }

    /*  Store Size  */
    public function store(StoreSize $request)
    {

        $size = Size::create($request->validated());
        return response()->json(['message' => 'Size Created Successfully', 'Size' => new SizeResource($size)]);
    }

    /*  Update Size By Id  */
    public function update(UpdateSize $request, Size $size)
    {
        $size->update($request->validated());
        return response()->json(['message' => 'Size Update Successfully', 'Size' => new SizeProductResource($size)]);
    }

    /*  delete Size  By Id */
    public function destroy(Size $size)
    {
        $size->delete();
        return response()->json(['message' => 'Size deleted successfully']);
    }
    /*  Show  Product related Size_Id */
    // Not completed
    public function show(Size $size)
    {
/*        if (isset($size->products)) {
            return response()->json(['products' => ProductResource::collection($size->products)]);
        }
        return response()->json(['products' => 'Not found Products for this Size']);*/
    }
}
