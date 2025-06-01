<?php


namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    public function index() {
        return response()->json(Customer::with('product')->get());
    }

    public function store(Request $request) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $total_price = $product->price * $request->quantity;

        $customer = Customer::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
        ]);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer) {
        return response()->json($customer->load('product'));
    }

    public function update(Request $request, Customer $customer) {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $total_price = $product->price * $request->quantity;

        $customer->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
        ]);

        return response()->json($customer);
    }

    public function destroy(Customer $customer) {
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

