<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFactory;
use App\Http\Resources\FactoryResource;
use App\Models\Factory;

class FactoryController extends Controller
{
    /*  Show All Factory  */
    public function index()
    {
        $factories = Factory::select('id', 'name', 'price')->get();
        return response()->json(['factory' => FactoryResource::collection($factories)]);
    }

    /*  Store Factory  */
    public function store(StoreFactory $request)
    {
        $factory = Factory::create($request->validated());
        // Bill::create(['factory_id' => $factory->id]);
        return response()->json(['message' => 'تم اضاقة خامة بنجاح', 'factory' => new FactoryResource($factory)]);
    }
}
