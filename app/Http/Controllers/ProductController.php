<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for product management"
 * )
 */
class ProductController extends Controller
{
    private const IMAGE_PATH = 'images/products';

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

    // This method returns an HTML view for the admin panel.
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "category_id", "stock_quantity", "price"},
     *             @OA\Property(property="name", type="string", example="Sample Product"),
     *             @OA\Property(property="description", type="string", example="Product description here"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="brand", type="string", example="Sample Brand"),
     *             @OA\Property(property="stock_quantity", type="integer", example=10),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="discount", type="number", format="float", example=10.00),
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
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'required|integer',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')
                ->store(self::IMAGE_PATH, 'public');
        }

        $product = Product::create($validated);

        if (isset($validated['discount']) && isset($validated['price'])) {
            $product->price_after_discount = $product->price * (1 - ($validated['discount'] / 100));
            $product->save();
        }

        return redirect()->route('admin.products')->with('success', 'Product added successfully!');
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
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="brand", type="string", example="Updated Brand"),
     *                 @OA\Property(property="stock_quantity", type="integer", example=5),
     *                 @OA\Property(property="price", type="number", format="float", example=149.99),
     *                 @OA\Property(property="discount", type="number", format="float", example=5.00),
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
            'discount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'stock_quantity' => 'sometimes|integer',
            'product_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        if ($request->hasFile('product_image')) {
            if ($product->product_image) {
                Storage::disk('public')->delete($product->product_image);
            }

            $validated['product_image'] = $request->file('product_image')
                ->store(self::IMAGE_PATH, 'public');
        }

        $product->update($validated);

        if (isset($validated['discount']) && isset($validated['price'])) {
            $product->price_after_discount = $product->price * (1 - ($validated['discount'] / 100));
            $product->save();
        }

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
     *         response=200,
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

        if ($product->product_image) {
            Storage::disk('public')->delete($product->product_image);
        }

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/products/discounted",
     *     tags={"Products"},
     *     summary="Get all discounted products with discount amounts and final prices",
     *     @OA\Response(
     *         response=200,
     *         description="Discounted products retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Sample Product"),
     *                 @OA\Property(property="price", type="number", format="float", example=99.99),
     *                 @OA\Property(property="discount", type="number", format="float", example=10),
     *                 @OA\Property(property="price_after_discount", type="number", format="float", example=89.99)
     *             )
     *         )
     *     )
     * )
     */
    public function discountedProducts()
    {
        $discountedProducts = Product::discounted()->get();

        return response()->json($discountedProducts, 200);
    }

    public function discountedProductsByFilteringCollection()
    {
        $allProducts = Product::all();
        $discountedProducts = $allProducts->filter(function ($product) {
            return $product->discount > 0;
        });

        return response()->json($discountedProducts->values(), 200);
    }
}
