<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show login form.
     */
    public function showLoginForm(): View
    {
        $this->captureIntendedUrl();
        return view('auth.login');
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm(): View
    {
        $this->captureIntendedUrl();
        return view('auth.register');
    }

    /**
     * Capture the previous URL as intended if not coming from auth routes.
     */
    private function captureIntendedUrl(): void
    {
        if (!session()->has('url.intended')) {
            $previous = url()->previous();
            // Don't capture if the user is bouncing between login and register
            if ($previous !== url()->current() && $previous !== route('login') && $previous !== route('register')) {
                session()->put('url.intended', $previous);
            }
        }
    }

    /**
     * Handle registration — always creates a CLIENT role user.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed',
                Password::min(6)->letters()->mixedCase()->numbers()->symbols()
            ],
            'password_confirmation' => ['required'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => User::ROLE_CLIENT, // Always client on self-registration
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'))
            ->with('message', 'Welcome! Your account has been created.');
    }

    /**
     * Handle login — redirect to the correct dashboard by role.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'login_err' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        // Super Admin and Hotel Admin must always land on their own dashboard.
        // The ?redirect= query param is only honoured for regular clients so that
        // the "Reserve" button can bring them back after login.
        if (in_array($role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN])) {
            return redirect()->intended($this->redirectByRole($role));
        }

        // For clients: honour the ?redirect= query param (e.g. Reserve button flow).
        $redirect = $request->query('redirect');
        if (is_string($redirect) && $redirect !== '') {
            $resolved = $this->resolvePostLoginRedirect($redirect, $request);
            if (is_string($resolved) && $resolved !== '') {
                return redirect()->to($resolved);
            }
        }

        return redirect()->intended($this->redirectByRole($role));
    }

    /**
     * Destroy the authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Resolve post-login redirect based on user role.
     */
    private function redirectByRole(string $role): string
    {
        return match ($role) {
            User::ROLE_SUPERADMIN => route('superadmin.index'),
            User::ROLE_ADMIN      => route('admin.index'),
            default               => route('home'),
        };
    }

    /**
     * Resolve the `redirect` query parameter used by the Reserve button.
     *
     * Security: only allow internal URLs/paths, and never allow arbitrary open redirects.
     */
    private function resolvePostLoginRedirect(string $redirect, Request $request): ?string
    {
        $redirect = trim($redirect);
        if ($redirect === '') {
            return null;
        }

        $parsed = parse_url($redirect);
        $path = $parsed['path'] ?? $redirect;
        $host = $parsed['host'] ?? null;
        $appUrlHost = parse_url(config('app.url'), PHP_URL_HOST);

        // Allow only internal redirects to prevent open-redirect issues.
        if (
            $host !== null
            && $appUrlHost !== null
            && !hash_equals($request->getHost(), $host)
            && !hash_equals($appUrlHost, $host)
        ) {
            return null;
        }
        if ($host === null && !str_starts_with($path, '/')) {
            return null;
        }

        // If the redirect is a hotel page, send the user to the reservation form.
        // Accept both:
        // - /hotels/{hotel}
        // - /hotels/{hotel}/reserve
        if (preg_match('#/hotels/(\\d+)(?:/reserve)?#', $path, $m)) {
            $hotelId = (int) $m[1];

            $query = [];
            if (!empty($parsed['query'])) {
                parse_str($parsed['query'], $query);
            }

            // If we ever receive `room` here, keep it to pre-select the room.
            if (isset($query['room']) && is_numeric($query['room'])) {
                return route('reservations.create', $hotelId) . '?room=' . urlencode((string) $query['room']);
            }

            return route('reservations.create', $hotelId);
        }

        // Otherwise, redirect back to the internal path as-is.
        return $path;
    }
}