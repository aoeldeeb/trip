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
        $user = $request->user();
        
        // Check if user is authenticated and is an admin
        // For now, we'll check if the user's email contains 'admin' or has an 'is_admin' field
        // In a real app, you might have a dedicated admin table or role system
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
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
            return response()->json([
                'message' => 'Access denied. Admin privileges required.'
            ], 403);
        }

        return $next($request);
    }
}
