<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Customers",
 *     description="API Endpoints for customer management"
 * )
 */
class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="Get list of customers",
     *     description="Returns all customers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="Create a new customer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);
//$validated['user_id'] = Auth::id();
        $customer = Customer::create($validated);
        // $customer = Customer::create([...$validated , 'user_id' => Auth::id()]);
        return response()->json($customer, 201);
    }

     /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Get a single customer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     )
     * )
     */
    public function show($id)
    {
        $customer = Customer::with('orders')->findOrFail($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json($customer, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Update a customer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", example="updated.email@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->update($request->all());

        return response()->json($customer, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Delete a customer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Customer ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }
}
