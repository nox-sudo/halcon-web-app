<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Lista general ─────────────────────────────
    public function index()
    {
        $users = User::with('role')->orderBy('is_active', 'desc')->orderBy('name')->paginate(20);
        return view('users.index', compact('users'));
    }

    // ── Formulario creación ───────────────────────
    public function create()
    {
        $roles = Role::all();
        return view('users.form', compact('roles'));
    }

    // ── Guardar nuevo usuario ─────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$validated['name']} creado correctamente.");
    }

    // ── Formulario edición ────────────────────────
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.form', compact('user', 'roles'));
    }

    // ── Actualizar usuario ────────────────────────
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'role_id'   => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$user->name} actualizado.");
    }

    // ── Activar / Desactivar ──────────────────────
    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $msg = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$user->name} {$msg}.");
    }
}
