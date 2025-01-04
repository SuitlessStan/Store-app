<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for product management"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get list of products",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "price", "product_image"},
     *             @OA\Property(property="name", type="string", example="Sample Product"),
     *             @OA\Property(property="description", type="string", example="Product description here"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="product_image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
           'category'=>'string',
            'brand'=>'string',
            'stock_quantity'=>'required|boolean',
            'price' => 'required|numeric',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',

        ]);

        // Handle file upload
        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')->store('images/products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Product added successfully!');
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get a single product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Updated Product"),
     *                 @OA\Property(property="description", type="string", example="Updated description"),
     *                 @OA\Property(property="price", type="number", format="float", example=149.99),
     *                 @OA\Property(property="product_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'price' => 'sometimes|numeric',
            'description' => 'nullable|string',
            'category' => 'sometimes|string|max:255',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'sometimes|integer',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Optional for updates
        ]);

        // Update Image if Provided
        if ($request->hasFile('product_image')) {
            // Delete Old Image
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }

            $imagePath = $request->file('product_image')->store('products', 'public');
            $validated['product_image'] = $imagePath;
        }

        $product->update($validated);

        return response()->json($product, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        // Delete Image
        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }

        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
