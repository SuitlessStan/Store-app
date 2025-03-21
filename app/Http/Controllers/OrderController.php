<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     *     summary="Get list of user's orders",
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
        $user = auth()->user();
        $orders = $user->orders()->with('orderDetails.product')->get();
        return response()->json($orders);
    }

    /**
     * Create a new order using the authenticated user and a provided cart_id.
     *
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Create a new order with cart_id",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_date", "total_amount", "status", "cart_id"},
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
            'order_date'      => 'required|date',
            'total_amount'    => 'required|numeric',
            'status'          => 'required|string',
            'address_id'      => 'required|exists:addresses,id',
            'is_home_delivery'=> 'nullable|boolean',
            'cart_id'         => 'required|exists:carts,id'
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
        $order = Order::with('orderDetails.product')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        return response()->json($order, 200);
    }

    /**
     * Checkout: Create an order from the authenticated user's cart, process payment and delivery options, and clear the cart.
     *
     * @OA\Post(
     *     path="/api/checkout",
     *     tags={"Orders"},
     *     summary="Checkout: Create an order from the authenticated user's cart, process payment and delivery options, and clear the cart",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"address_id", "payment_method", "is_home_delivery"},
     *             @OA\Property(
     *                 property="address_id",
     *                 type="integer",
     *                 example=1,
     *                 description="The ID of the shipping address for the order"
     *             ),
     *             @OA\Property(
     *                 property="payment_method",
     *                 type="integer",
     *                 enum={0, 1},
     *                 example=0,
     *                 description="Payment method: 0 for cash, 1 for visa"
     *             ),
     *             @OA\Property(
     *                 property="is_home_delivery",
     *                 type="boolean",
     *                 example=true,
     *                 description="True if the order is to be delivered; false for in-person pickup"
     *             ),
     *             @OA\Property(
     *                 property="delivery_price",
     *                 type="number",
     *                 format="float",
     *                 example=10.00,
     *                 description="Delivery price; required if is_home_delivery is true, 0 otherwise"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully; cart cleared and order details returned",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(response=400, description="Cart is empty"),
     *     @OA\Response(response=500, description="Checkout failed")
     * )
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }

        $validated = $request->validate([
            'address_id'     => 'required|exists:addresses,id',
            'payment_method' => 'required|in:0,1', // 0 for cash, 1 for visa
            'is_home_delivery' => 'required|boolean',
            'delivery_price' => 'required_if:is_home_delivery,1|numeric|min:0'
        ]);

        $totalAmount = 0;
        foreach ($cart->items as $item) {
            $totalAmount += $item->quantity * $item->product->price;
        }

        $isHomeDelivery = $validated['is_home_delivery'];
        $deliveryPrice = $isHomeDelivery ? $validated['delivery_price'] : 0;
        $totalAmount += $deliveryPrice;

        DB::beginTransaction();
        try {
            $order = $user->orders()->create([
                'address_id'     => $validated['address_id'],
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                'payment_method' => $validated['payment_method'],
                'is_home_delivery' => $isHomeDelivery,
                'delivery_price' => $deliveryPrice,
            ]);

            foreach ($cart->items as $item) {
                $order->orderDetails()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            $cart->items()->delete();
            DB::commit();

            return response()->json($order->load('orderDetails.product'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Checkout failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
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
            'order_date'      => 'sometimes|date',
            'total_amount'    => 'sometimes|numeric',
            'status'          => 'sometimes|string',
            'delivery_address'=> 'nullable|string',
            'is_home_delivery'=> 'nullable|boolean',
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
