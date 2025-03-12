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
        $orders = Order::with('user', 'address', 'orderDetails')->get();
        return response()->json($orders, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/user/orders",
     *     tags={"Orders"},
     *     summary="Get all orders for the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User orders retrieved successfully",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function userOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['address', 'orderDetails.product'])
            ->get();

        return response()->json($orders, 200);
    }

    /**
     * Create a new order using the authenticated user and a provided product_id.
     *
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order with product_id",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "total_amount", "status", "product_id"},
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="total_amount", type="number", format="float", example=99.99),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="address_id", type="integer", example=1),
     *             @OA\Property(property="is_home_delivery", type="boolean", example=true),
     *             @OA\Property(property="cart_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'address_id' => 'required|exists:addresses,id',
            'is_home_delivery' => 'nullable|boolean',
            'cart_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $validated['user_id'] = $user->id;

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
        $order = Order::with('user', 'address', 'orderDetails')->findOrFail($id);
        return response()->json($order, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/checkout",
     *     tags={"Orders"},
     *     summary="Checkout: Place an order and empty the cart",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "address_id"},
     *             @OA\Property(property="order_date", type="string", format="date", example="2025-03-01"),
     *             @OA\Property(property="address_id", type="integer", example=1),
     *             @OA\Property(property="delivery_address", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="is_home_delivery", type="boolean", example=true),
     *             @OA\Property(property="payment_method", type="integer", example=0, description="0 for cash, 1 for visa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Checkout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Checkout successful"),
     *             @OA\Property(property="order", ref="#/components/schemas/Order")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Cart is empty")
     * )
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'order_date' => 'required|date',
            'address_id' => 'required|exists:addresses,id',
            'delivery_address' => 'nullable|string',
            'is_home_delivery' => 'nullable|boolean',
            'payment_method' => 'required|in:0,1'
        ]);

        $user = Auth::user();

        $cartItems = \App\Models\Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $orderData = array_merge($validated, [
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
        ]);

        $order = Order::create($orderData);

        foreach ($cartItems as $item) {
            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Empty cart
        \App\Models\Cart::where('user_id', $user->id)->delete();

        // load the order with its related data.
        $order = Order::with(['user', 'address', 'orderDetails.product'])->find($order->id);

        return response()->json([
            'message' => 'Checkout successful',
            'order' => $order,
        ], 201);
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
            'order_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric',
            'status' => 'sometimes|string',
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
