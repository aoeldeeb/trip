<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for web session admin access (for dashboard)
        if ($request->is('admin/*') && session('admin_logged_in')) {
            return $next($request);
        }

        // Check for API authentication
        $user = $request->user();
        
        // Check if user is authenticated and is an admin
        if (!$user) {
            // For web requests, redirect to login
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            } else {
                return redirect()->route('admin.login');
            }
        }

        // Simple admin check - in production you'd want a proper role system
        $isAdmin = false;
        
        if (method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole('admin');
        } elseif (property_exists($user, 'is_admin')) {
            $isAdmin = $user->is_admin;
        } elseif (property_exists($user, 'email') && $user->email) {
            $isAdmin = str_contains($user->email, 'admin');
        }

        if (!$isAdmin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied. Admin privileges required.'
                ], 403);
            } else {
                return redirect()->route('admin.login')->with('error', 'Admin access required.');
            }
        }

        return $next($request);
    }
}