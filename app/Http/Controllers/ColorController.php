<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColor;
use App\Http\Requests\UpdateColor;
use App\Http\Resources\ColorProductResource;
use App\Http\Resources\ColorResource;
use App\Models\Color;

class ColorController extends Controller
{
    /*  Show All Color  */
    public function index()
    {
        $colors = Color::select('id', 'name')->get();
        return response()->json($colors);
    }

    /*  Store Color  */
    public function store(StoreColor $request)
    {
        $color = Color::create($request->validated());
        return response()->json(['message' => 'Color Created Successfully', 'Color' => new ColorResource($color)]);
    }

    /*  Update Color By Id  */
    public function update(UpdateColor $request, Color $color)
    {
        $color->update($request->validated());
        return response()->json(['message' => 'Color Update Successfully', 'Color' => new ColorProductResource($color)]);
    }

    /*  delete Color  By Id */
    public function destroy(Color $color)
    {
        $color->delete();
        return response()->json(['Color' => 'Color deleted successfully']);
    }

    /*  Show  Product related color_Id */
    // Not completed
    public function show(Color $color)
    {
        //$color->;
/*        if (isset($color->products)) {
            return response()->json(['products' => ProductResource::collection($color->products)]);
        }
        return response()->json(['products' => 'Not found Products for this color']);*/
    }
}
