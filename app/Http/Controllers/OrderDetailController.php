<?php

namespace App\Http\Controllers;

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
     *     path="/api/order-details",
     *     tags={"Order Details"},
     *     summary="Get list of order details",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/OrderDetail"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(OrderDetail::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/order-details",
     *     tags={"Order Details"},
     *     summary="Create a new order detail",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id", "product_id", "quantity", "price"},
     *             @OA\Property(property="order_id", type="integer", example=1),
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $orderDetail = OrderDetail::create($validated);

        return response()->json($orderDetail, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Get a single order detail",
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
    public function show($id)
    {
        $orderDetail = OrderDetail::find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        return response()->json($orderDetail, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Update an order detail",
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
    public function update(Request $request, $id)
    {
        $orderDetail = OrderDetail::find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'Order detail not found'], 404);
        }

        $validated = $request->validate([
            'quantity' => 'sometimes|required|integer|min:1',
            'unit_price' => 'sometimes|required|numeric|min:0',
        ]);

        $orderDetail->update($validated);

        return response()->json($orderDetail, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/order-details/{id}",
     *     tags={"Order Details"},
     *     summary="Delete an order detail",
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
    public function destroy($id)
    {
        $orderDetail = OrderDetail::find($id);

        if (!$orderDetail) {
            return response()->json(['message' => 'OrderDetail not found'], 404);
        }

        $orderDetail->delete();
        return response()->json(null, 204);
    }
}