<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Profiles",
 *     description="API Endpoints for Profiles"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profiles",
     *     tags={"Profiles"},
     *     summary="Get list of profiles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Profile"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Profile::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/profiles",
     *     tags={"Profiles"},
     *     summary="Create a new profile",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "bio"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="bio", type="string", example="This is a sample bio.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Profile created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           // 'user_id' => 'required|exists:users,id',
            'bio' => 'required|string',
        ]);

        $profile = Auth::user()->profile()->create($validated);

        return response()->json($profile, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/profiles/{id}",
     *     tags={"Profiles"},
     *     summary="Get a single profile",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Profile ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     )
     * )
     */
    public function show($id)
    {
        $profile = Profile::findOrFail($id);
        return response()->json($profile, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/profiles/{id}",
     *     tags={"Profiles"},
     *     summary="Update a profile",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Profile ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="bio", type="string", example="Updated bio.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Profile")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);

        $validated = $request->validate([
            'bio' => 'string',
        ]);

        $profile->update($validated);

        return response()->json($profile, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/profiles/{id}",
     *     tags={"Profiles"},
     *     summary="Delete a profile",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Profile ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Profile deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->delete();

        return response()->json(null, 204);
    }
}
