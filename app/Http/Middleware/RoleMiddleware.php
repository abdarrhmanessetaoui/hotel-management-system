<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:superadmin')
     *   ->middleware('role:admin,superadmin')  ← multiple roles allowed
     *
     * @param  string  ...$roles  One or more allowed roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Must be authenticated first
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user's role matches any of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Role mismatch — redirect to the correct dashboard instead of a blank 403
        return $this->redirectByRole($user->role);
    }

    /**
     * Redirect the user to their correct area when they try to
     * access a panel they don't belong to.
     */
    private function redirectByRole(string $role): Response
    {
        $destination = match ($role) {
            User::ROLE_SUPERADMIN => route('superadmin.index'),
            User::ROLE_ADMIN      => route('admin.index'),
            default               => route('home'),
        };

        return redirect($destination)
            ->with('error', 'You do not have permission to access that area.');
    }
}