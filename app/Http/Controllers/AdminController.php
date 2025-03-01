<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * This method retrieves counts for products, suppliers, and orders,
     * along with the 10 most recent activities, and returns the admin dashboard view.
     */
    public function index()
    {
        $totalProducts  = Product::count();
        $totalSuppliers = Supplier::count();
        $totalOrders    = Order::count();

        $recentProducts  = Product::orderBy('created_at', 'desc')->take(10)->get();
        $recentSuppliers = Supplier::orderBy('created_at', 'desc')->take(10)->get();
        $recentOrders    = Order::orderBy('created_at', 'desc')->take(10)->get();

        $recentActivities = collect();

        foreach ($recentProducts as $product) {
            $recentActivities->push([
                'type'        => 'Product',
                'description' => "Product added: {$product->name}",
                'created_at'  => $product->created_at,
            ]);
        }

        foreach ($recentSuppliers as $supplier) {
            $recentActivities->push([
                'type'        => 'Supplier',
                'description' => "Supplier added: {$supplier->name}",
                'created_at'  => $supplier->created_at,
            ]);
        }

        foreach ($recentOrders as $order) {
            $recentActivities->push([
                'type'        => 'Order',
                'description' => "Order placed: #{$order->id} ({$order->status})",
                'created_at'  => $order->created_at,
            ]);
        }

        $recentActivities = $recentActivities->sortByDesc('created_at')->take(10);

        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalSuppliers', 
            'totalOrders', 
            'recentActivities'
        ));
    }

    /**
     * Show the form to create a new product.
     */
    public function createProduct()
    {
        return view('admin.create-product');
    }

    /**
     * Store a new product.
     *
     * Validates the request data, creates a product record, and then redirects
     * back to the admin dashboard with a success message.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'price'    => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Product added successfully.');
    }

    /**
     * Show the form to create a new supplier.
     */
    public function createSupplier()
    {
        return view('admin.create-supplier');
    }

    /**
     * Store a new supplier.
     *
     * Validates the request data, creates a supplier record, and then redirects
     * back to the admin dashboard with a success message.
     */
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Supplier added successfully.');
    }
}
