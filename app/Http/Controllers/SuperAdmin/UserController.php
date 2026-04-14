<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $hotels = \App\Models\Hotel::orderBy('name')->get();
        return view('superadmin.users.index', compact('users', 'hotels'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('superadmin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:client,admin'], // Restricted superadmin
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('superadmin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $hotels = \App\Models\Hotel::orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'hotels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        // Only allow role change if target is NOT a superadmin or if requester is trying to demote a superadmin (which we block)
        if ($user->role !== User::ROLE_SUPERADMIN) {
            $rules['role'] = ['required', 'string', 'in:client,admin'];
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Maintain role if it was superadmin, otherwise update if provided
        if ($user->role !== User::ROLE_SUPERADMIN && $request->has('role')) {
            $data['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Handle hotel assignment
        if ($user->role === User::ROLE_ADMIN) {
            $request->validate(['hotel_id' => ['nullable', 'exists:hotels,id']]);

            // Remove from existing hotel
            if ($user->hotel) {
                $user->hotel->update(['admin_id' => null]);
            }
            // Assign to new hotel
            if ($request->hotel_id) {
                \App\Models\Hotel::where('id', $request->hotel_id)->update(['admin_id' => $user->id]);
            }
        }

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte de {$user->name} a été {$status} avec succès.");
    }

    public function assignHotel(Request $request, User $user)
    {
        if ($user->role !== User::ROLE_ADMIN) {
            return back()->with('error', 'Seul un Administrateur Hôtelier peut être assigné à un hôtel.');
        }

        $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
        ]);

        // Remove from existing hotel if there is one
        if ($user->hotel) {
            $user->hotel->update(['admin_id' => null]);
        }

        // Assign to new hotel
        if ($request->hotel_id) {
            \App\Models\Hotel::where('id', $request->hotel_id)->update(['admin_id' => $user->id]);
            return back()->with('success', "L'hôtel a été assigné avec succès à {$user->name}.");
        }

        return back()->with('success', "L'assignation à un hôtel a été retirée pour {$user->name}.");
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->id() || $user->role === User::ROLE_SUPERADMIN) {
            return back()->with('error', 'Vous ne pouvez pas modifier le rôle d\'un Super Admin.');
        }

        $request->validate([
            'role' => ['required', 'string', 'in:client,admin'], // Restricted superadmin
        ]);

        $user->update(['role' => $request->role]);

        // Unassign hotel if demoted from admin
        if ($user->role !== User::ROLE_ADMIN && $user->hotel) {
            $user->hotel->update(['admin_id' => null]);
        }

        return back()->with('success', "Le rôle de {$user->name} a été mis à jour.");
    }

}
