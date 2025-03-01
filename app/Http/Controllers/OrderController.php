<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for Orders"
 * )
 */
class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('customer')->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "order_date", "total_amount", "status"},
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *             @OA\Property(property="status", type="string", example="Pending")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'order_date'   => 'required|date',
            'total_amount' => 'required|numeric',
            'status'       => 'required|string',
        ]);

        $order = Order::create($validated);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::with('customer', 'orderDetails')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate only the updatable fields; adjust rules as needed.
        $validated = $request->validate([
            'order_date'   => 'sometimes|date',
            'total_amount' => 'sometimes|numeric',
            'status'       => 'sometimes|string',
        ]);

        $order->update($validated);

        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        // Return no content for a 204 response.
        return response()->noContent();
    }
}
