<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Tag(
 *     name="Password Reset Link",
 *     description="API Endpoints for requesting password reset links"
 * )
 */
class   PasswordResetLinkController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/password/forgot",
     *     summary="Send a password reset link",
     *     tags={"Password Reset Link"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Password reset link sent successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=400, description="Unable to send reset link")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent successfully'], 200);
        }

        return response()->json(['message' => __($status)], 400);
    }
}
