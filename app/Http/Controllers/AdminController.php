<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin Dashboard and Management APIs"
 * )
 */
class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/dashboard",
     *     summary="View Admin Dashboard",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Displays the admin dashboard",
     *         @OA\JsonContent(
     *             @OA\Property(property="totalProducts", type="integer", example=10),
     *             @OA\Property(property="totalSuppliers", type="integer", example=5)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalOrders = Order::count();

        $recentActivities = Activity::orderBy('created_at', 'desc')->take(10)->get();

        return view('admin.dashboard', compact('totalProducts', 'totalSuppliers', 'totalOrders', 'recentActivities'));
    }

    /**
     * @OA\Get(
     *     path="/admin/products/create",
     *     summary="Show form to create a new product",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Displays the create product form"
     *     )
     * )
     */
    public function createProduct()
    {
        return view('admin.create-product');
    }

    /**
     * @OA\Post(
     *     path="/admin/products",
     *     summary="Add a new product",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "quantity"},
     *             @OA\Property(property="name", type="string", example="Sample Product"),
     *             @OA\Property(property="price", type="number", example=99.99),
     *             @OA\Property(property="quantity", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully"
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/admin/suppliers/create",
     *     summary="Show form to create a new supplier",
     *     tags={"Admin"},
     *     @OA\Response(
     *         response=200,
     *         description="Displays the create supplier form"
     *     )
     * )
     */
    public function createSupplier()
    {
        return view('admin.create-supplier');
    }

    /**
     * @OA\Post(
     *     path="/admin/suppliers",
     *     summary="Add a new supplier",
     *     tags={"Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Supplier Name"),
     *             @OA\Property(property="email", type="string", example="supplier@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Supplier created successfully"
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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
