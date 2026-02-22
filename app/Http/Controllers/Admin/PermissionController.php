<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:permissions,slug'],
            'group_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        Permission::create($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:permissions,slug,' . $permission->id],
            'group_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $permission->update($validated);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function toggleStatus(Permission $permission)
    {
        $permission->update(['status' => $permission->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Permission status updated.');
    }
}
