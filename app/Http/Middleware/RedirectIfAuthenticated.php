<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Applied to "guest" routes (login, register).
     * If the user is already authenticated, send them to their dashboard.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($this->redirectByRole(Auth::user()->role));
            }
        }

        return $next($request);
    }

    /**
     * Resolve the correct post-login destination by user role.
     */
    private function redirectByRole(string $role): string
    {
        return match ($role) {
            User::ROLE_SUPERADMIN => route('superadmin.index'),
            User::ROLE_ADMIN      => route('admin.index'),
            default               => route('home'),
        };
    }
}
