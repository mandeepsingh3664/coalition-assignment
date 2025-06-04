<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productm;

class Product extends Controller
{
    public function index()
    {
        $products = Productm::getAllFromJson();
        return view('index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'created_at' => now()->toDateTimeString(),
            'total_value' => $request->quantity * $request->price
        ];

        Productm::saveToJson($data);

        return response()->json(['success' => true, 'product' => $data, 'total' => count($data)+1]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $file = public_path('inventory.json');
        $products = json_decode(file_get_contents($file), true) ?: [];

        $products[$id] = [
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'created_at' => $products[$id]['created_at'],
            'total_value' => $request->quantity * $request->price
        ];

        file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT));

        return response()->json(['success' => true, 'product' => $products[$id], 'total' => count($products)+1]);
    }
}
