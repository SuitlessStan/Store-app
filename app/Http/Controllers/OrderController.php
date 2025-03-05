<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for Orders"
 * )
 */
class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get list of orders",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Order")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $orders = Order::with('customer', 'orderDetails')->get();
        return response()->json($orders, 200);
    }

    /**
     * Create a new order using the authenticated user's customer record.
     *
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "total_amount", "status"},
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="delivery_address", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="is_home_delivery", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=400, description="Customer record not found for the user"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_date'       => 'required|date',
            'total_amount'     => 'required|numeric',
            'status'           => 'required|string',
            'delivery_address' => 'nullable|string',
            'is_home_delivery' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        if (!$user->customer) {
            return response()->json(['message' => 'No customer record found for the user'], 400);
        }
        // Use the authenticated user's customer id.
        $validated['customer_id'] = $user->customer->id;

        $order = Order::create($validated);

        return response()->json($order, 201);
    }

    /**
     * Display the specified order.
     *
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Get a single order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function show($id)
    {
        $order = Order::with('customer', 'orderDetails')->findOrFail($id);
        return response()->json($order, 200);
    }

    /**
     * Update the specified order.
     *
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Update an order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *             @OA\Property(property="status", type="string", example="Completed"),
     *             @OA\Property(property="delivery_address", type="string", example="456 Another St, City, Country"),
     *             @OA\Property(property="is_home_delivery", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=404, description="Order not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_date'       => 'sometimes|date',
            'total_amount'     => 'sometimes|numeric',
            'status'           => 'sometimes|string',
            'delivery_address' => 'nullable|string',
            'is_home_delivery' => 'nullable|boolean',
        ]);

        $order->update($validated);

        return response()->json($order, 200);
    }

    /**
     * Remove the specified order.
     *
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Delete an order",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Order deleted successfully"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->noContent();
    }
}
