<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Email Verification",
 *     description="API Endpoints for email verification"
 * )
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/email/verification-notification",
     *     summary="Resend email verification link",
     *     tags={"Email Verification"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Verification link sent successfully"),
     *     @OA\Response(response=400, description="Email already verified"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent successfully'], 200);
    }
}
