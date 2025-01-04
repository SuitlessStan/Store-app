<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="API Endpoints for managing shopping cart"
 * )
 */
class CartController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Get the cart items for the authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Cart retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('product')->get();
        return response()->json($cart);
    }

    /**
     * @OA\Post(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Add product to cart",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product added to cart"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json($cart, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/cart/{id}",
     *     tags={"Cart"},
     *     summary="Update cart item quantity",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cart updated successfully"),
     *     @OA\Response(response=404, description="Cart item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return response()->json($cart);
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/{id}",
     *     tags={"Cart"},
     *     summary="Remove product from cart",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Cart item removed successfully"),
     *     @OA\Response(response=404, description="Cart item not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return response()->json(['message' => 'Cart item removed successfully'], 204);
    }
}
