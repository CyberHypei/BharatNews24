<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->latest()->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('status', 'active')->get()->groupBy('group_name');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create($validated);
        $role->permissions()->sync($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::where('status', 'active')->get()->groupBy('group_name');
        $role->load('permissions');
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug,' . $role->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role->update($validated);
        $role->permissions()->sync($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role that has users assigned.');
        }
        $role->permissions()->detach();
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function toggleStatus(Role $role)
    {
        $role->update(['status' => $role->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Role status updated.');
    }
}
