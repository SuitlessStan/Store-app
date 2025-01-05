<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')->with('error', 'Please log in first.');
        }

        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized Access.');
        }

        return $next($request);
    }
}
