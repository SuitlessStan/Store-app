<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Addresses",
 *     description="API Endpoints for managing user addresses"
 * )
 */
class AddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/addresses",
     *     summary="Get list of addresses for the authenticated user",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Addresses retrieved successfully",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Address")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $addresses = Auth::user()->addresses;
        return response()->json($addresses, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/addresses",
     *     summary="Create a new address for the authenticated user",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"address", "label"},
     *             @OA\Property(property="address", type="string", example="123 Main St, City, Country"),
     *             @OA\Property(property="label", type="string", example="home", enum={"home", "work"}),
     *             @OA\Property(property="latitude", type="number", format="float", example=40.712776),
     *             @OA\Property(property="longitude", type="number", format="float", example=-74.005974),
     *             @OA\Property(property="streetname", type="string", example="Main St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address'    => 'required|string|max:255',
            'label'      => 'required|in:home,work',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'streetname' => 'nullable|string|max:255',
        ]);

        $address = Auth::user()->addresses()->create($validated);

        return response()->json($address, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/addresses/{id}",
     *     summary="Update an existing address",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Address ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="address", type="string", example="456 New Address St, City, Country"),
     *             @OA\Property(property="label", type="string", example="work", enum={"home", "work"}),
     *             @OA\Property(property="latitude", type="number", format="float", example=41.000000),
     *             @OA\Property(property="longitude", type="number", format="float", example=-75.000000),
     *             @OA\Property(property="streetname", type="string", example="New Street")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Address")
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Unauthorized to modify this address")
     * )
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'address'    => 'sometimes|required|string|max:255',
            'label'      => 'sometimes|required|in:home,work',
            'latitude'   => 'sometimes|nullable|numeric',
            'longitude'  => 'sometimes|nullable|numeric',
            'streetname' => 'sometimes|nullable|string|max:255',
        ]);

        $address->update($validated);

        return response()->json($address, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     summary="Delete an address",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Address ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Address deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Unauthorized to delete this address")
     * )
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $address->delete();

        return response()->noContent();
    }
}
