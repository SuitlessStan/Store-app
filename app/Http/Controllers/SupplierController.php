<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Suppliers",
 *     description="API Endpoints for Suppliers"
 * )
 */
class SupplierController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/suppliers",
     *     tags={"Suppliers"},
     *     summary="Get list of suppliers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Supplier"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Supplier::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/suppliers",
     *     tags={"Suppliers"},
     *     summary="Create a new supplier",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "contact_person"},
     *             @OA\Property(property="name", type="string", example="Supplier Name"),
     *             @OA\Property(property="contact_person", type="string", example="supplier@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Supplier created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|email|unique:suppliers,contact_person',
            'address'=>'required|string',
            'phone'=>'required|numeric|digits:10'
            ]);

        $supplier = Supplier::create($validated);

        return response()->json($supplier, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Get a single supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Update a supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Supplier Name"),
     *             @OA\Property(property="contact_person", type="string", example="updated@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Supplier updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Supplier")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'contact_person' => 'email|unique:suppliers,contact_person,' . $id,
            'address'=>'required|string',
            'phone'=>'required|numeric|digits:10'
            ]);

        $supplier->update($validated);

        return response()->json($supplier, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Delete a supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Supplier ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Supplier deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(null, 204);
    }
}
