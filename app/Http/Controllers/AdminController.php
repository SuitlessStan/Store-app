<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();

        return view('admin.dashboard', compact('totalProducts', 'totalSuppliers'));
    }

    public function createProduct()
    {
        return view('admin.create-product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Product added successfully.');
    }

    public function createSupplier()
    {
        return view('admin.create-supplier');
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Supplier added successfully.');
    }
}
