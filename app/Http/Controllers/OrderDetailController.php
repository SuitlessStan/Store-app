<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Order Details",
 *     description="API Endpoints for managing order details/items"
 * )
 */
class OrderDetailController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders/{order}/order-details",
     *     tags={"Order Details"},
     *     summary="Get list of order details for a specific order",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/OrderDetail")
     *         )
     *     )
     * )
     */
    public function index(Order $order)
    {
        return response()->json(['order_details' => $order->orderDetails], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/orders/{order}/order-details",
     *     tags={"Order Details"},
     *     summary="Create a new order detail",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity", "price"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2),
     *             @OA\Property(property="price", type="number", format="float", example=49.99)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order detail created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderDetail")
     *     )
     * )
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'price'      => 'required|numeric|min:0',
        ]);

        $orderDetail = $order->orderDetails()->create($validated);
        return response()->json($orderDetail, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Get a single order detail",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order Detail ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OrderDetail")
     *     )
     * )
     */
    public function show(Order $order, $id)
    {
        $orderDetail = $order->orderDetails()->find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        return response()->json($orderDetail, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{order}/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Update an order detail",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order Detail ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=3),
     *             @OA\Property(property="price", type="number", format="float", example=59.99)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order detail updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderDetail")
     *     )
     * )
     */
    public function update(Request $request, Order $order, $id)
    {
        $orderDetail = $order->orderDetails()->find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $validated = $request->validate([
            'quantity' => 'sometimes|required|integer|min:1',
            'price'    => 'sometimes|required|numeric|min:0',
        ]);

        $orderDetail->update($validated);

        return response()->json($orderDetail, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{order}/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Delete an order detail",
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order Detail ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order detail deleted successfully"
     *     )
     * )
     */
    public function destroy(Order $order, $id)
    {
        $orderDetail = $order->orderDetails()->find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $orderDetail->delete();

        return response()->noContent();
    }
}
