<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrder;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all();
        return response()->json(['orders' => OrderResource::collection($orders)]);
    }

    public function store(StoreOrder $request)
    {
        $order = Order::create([$request->validated(), 'user_id' => $request->user()]);
        $product = Product::find($request['product_id']);
        // check Quantity == $request['quantity']
        if ($product->quantity < $request['quantity']) {
            return response()->json(['message' => 'Quantity Not Available']);
        }
        $product->update(['quantity' => $product->quantity - $request['quantity'], 'stock' => 1 + $product['stock']]);
        // decrease quantity  in Product
        // stock of product +1
        return response()->json(['order' => new OrderResource($order)]);
    }

    public function delete(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }

    public function restore(Order $order)
    {
        // delete Order
        // increase quantity  in Product
        // stock of product -1


    }
}
