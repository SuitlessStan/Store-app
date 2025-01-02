<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for User Authentication"
 * )
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form (Web-based).
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user for both Web and API access",
     *     description="Handles web-based login with redirects and API-based login that returns a token.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful - Web users are redirected, API users receive a token",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="abc123tokenvalue")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        // For Web Authentication
        if ($request->expectsJson() === false) {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Redirect to admin dashboard if admin, else normal dashboard
                return redirect()->intended(auth()->user()->is_admin ? '/admin/dashboard' : '/dashboard');
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // For API Authentication
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = Auth::attempt($request->only('email', 'password'));

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Log out the authenticated user (Web-based).
     *
     * Note: This method is for web logout only and does not affect API tokens.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out the authenticated API user",
     *     description="Invalidates all tokens for the currently authenticated user.",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized - Invalid or expired token"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function apiDestroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
