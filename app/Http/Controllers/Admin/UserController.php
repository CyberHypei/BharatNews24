<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('slug', $request->role));
        }
        $users = $query->latest()->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('status', 'active')->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'status' => ['required', 'in:active,inactive'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $plainPassword = $validated['password'];
        $validated['password'] = Hash::make($plainPassword);
        $roles = $validated['roles'];
        unset($validated['roles']);

        $user = User::create($validated);
        $user->roles()->sync($roles);

        Mail::to($user->email)->send(new WelcomeUserMail(
            $user,
            $plainPassword,
            route('login')
        ));

        return redirect()->route('admin.users.index')->with('success', 'User created successfully. A welcome email with credentials has been sent.');
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::where('status', 'active')->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'status' => ['required', 'in:active,inactive'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $roles = $validated['roles'];
        unset($validated['roles']);

        $user->update($validated);
        $user->roles()->sync($roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === request()->user()->id) {
            return back()->with('error', 'You cannot change your own status.');
        }
        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'User status updated.');
    }
}
