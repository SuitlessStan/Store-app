<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Email Verification",
 *     description="API Endpoints for email verification"
 * )
 */
class EmailVerificationPromptController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/email/verify",
     *     summary="Check email verification status",
     *     tags={"Email Verification"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Email is verified"),
     *     @OA\Response(response=400, description="Email is not verified"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function __invoke(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is verified'], 200);
        }

        return response()->json(['message' => 'Email is not verified'], 400);
    }
}