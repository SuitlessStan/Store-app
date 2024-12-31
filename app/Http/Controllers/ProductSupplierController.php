<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSupplier;
use App\Models\Product;
use App\Models\Supplier;

class ProductSupplierController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/product-suppliers",
     *     tags={"Product Suppliers"},
     *     summary="Get list of product suppliers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProductSupplier"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(ProductSupplier::with(['product', 'supplier'])->get(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/product-suppliers/create",
     *     summary="Show the form for creating a new resource",
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function create()
    {
        return view('product-supplier.create', [
            'products' => Product::all(),
            'suppliers' => Supplier::all()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/product-suppliers",
     *     tags={"Product Suppliers"},
     *     summary="Create a new product supplier",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "supplier_id"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="supplier_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product supplier created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductSupplier")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $productSupplier = ProductSupplier::create($validated);

        return response()->json([
            'message' => 'Product-Supplier relationship created successfully!',
            'data' => $productSupplier
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/product-suppliers/{id}",
     *     tags={"Product Suppliers"},
     *     summary="Get a single product supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductSupplier")
     *     )
     * )
     */
    public function show(string $id)
    {
        $productSupplier = ProductSupplier::with(['product', 'supplier'])->find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        return response()->json($productSupplier, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/product-suppliers/{id}",
     *     tags={"Product Suppliers"},
     *     summary="Update a specific product-supplier relationship",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "supplier_id"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="supplier_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductSupplier")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $productSupplier = ProductSupplier::find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        $productSupplier->update($validated);

        return response()->json([
            'message' => 'Product-Supplier relationship updated successfully!',
            'data' => $productSupplier
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/product-suppliers/{id}",
     *     tags={"Product Suppliers"},
     *     summary="Delete a product supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product supplier deleted successfully"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $productSupplier = ProductSupplier::find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        $productSupplier->delete();

        return response()->json(['message' => 'Product-Supplier relationship deleted successfully!'], 204);
    }
}
