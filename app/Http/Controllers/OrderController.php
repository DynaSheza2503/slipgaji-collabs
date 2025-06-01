<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller {
    public function index() {
        return response()->json(Order::with('product')->get());
    }

    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $product = Product::findOrFail($request->product_id);
        $total_price = $product->price * $request->quantity;
    
        $order = Order::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
        ]);
    
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
    }

    public function show($id) {
        $order = Order::with('product')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $order = Order::findOrFail($id);

        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $order->product_id = $request->product_id;
            $order->total_price = $product->price * $order->quantity;
        }

        if ($request->has('quantity')) {
            $product = Product::findOrFail($order->product_id);
            $order->quantity = $request->quantity;
            $order->total_price = $product->price * $request->quantity;
        }

        $order->save();

        return response()->json($order);
    }

    public function destroy($id) {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
