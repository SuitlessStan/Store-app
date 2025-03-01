<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSupplier;
use App\Models\Product;
use App\Models\Supplier;

class ProductSupplierController extends Controller
{
    public function index()
    {
        return response()->json(ProductSupplier::with(['product', 'supplier'])->get(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/product-suppliers/create",
     *     summary="Show the form for creating a new product supplier",
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function create()
    {
        return view('product-supplier.create', [
            'products'  => Product::all(),
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
     *             required={"product_id", "supplier_id", "cost_price"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="supplier_id", type="integer", example=2),
     *             @OA\Property(property="cost_price", type="number", format="float", example=10.50)
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
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'cost_price'  => 'required|numeric',
        ]);

        $productSupplier = ProductSupplier::create($validated);

        return response()->json([
            'message' => 'Product-Supplier relationship created successfully!',
            'data'    => $productSupplier
        ], 201);
    }

    public function show(string $id)
    {
        $productSupplier = ProductSupplier::with(['product', 'supplier'])->find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        return response()->json($productSupplier, 200);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'cost_price'  => 'required|numeric',
        ]);

        $productSupplier = ProductSupplier::find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        $productSupplier->update($validated);

        return response()->json([
            'message' => 'Product-Supplier relationship updated successfully!',
            'data'    => $productSupplier
        ], 200);
    }

    public function destroy(string $id)
    {
        $productSupplier = ProductSupplier::find($id);

        if (!$productSupplier) {
            return response()->json(['message' => 'Product-Supplier relationship not found'], 404);
        }

        $productSupplier->delete();

        return response()->noContent();
    }
}
