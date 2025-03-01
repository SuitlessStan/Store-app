<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="API Endpoints for managing product favorites"
 * )
 */
class FavoritesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     tags={"Favorites"},
     *     summary="Get list of favorite products for authenticated user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="List of favorites retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())->with('product')->get();
        return response()->json($favorites, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     tags={"Favorites"},
     *     summary="Add product to favorites",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product added to favorites"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json($favorite, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites/{id}",
     *     tags={"Favorites"},
     *     summary="Remove product from favorites",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Favorite removed successfully"),
     *     @OA\Response(response=404, description="Favorite not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())->findOrFail($id);
        $favorite->delete();

        return response()->json(['message' => 'Favorite removed successfully'], 200);
    }
}
