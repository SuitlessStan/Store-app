<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Password Confirmation",
 *     description="API Endpoints for confirming user passwords"
 * )
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/confirm-password",
     *     summary="Confirm user's password",
     *     tags={"Password Confirmation"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password confirmed successfully"),
     *     @OA\Response(response=422, description="Validation error - incorrect password")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        return response()->json(['message' => 'Password confirmed successfully'], 200);
    }
}
