<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Create Post', 'slug' => 'create-post', 'group_name' => 'post'],
            ['name' => 'Edit Post', 'slug' => 'edit-post', 'group_name' => 'post'],
            ['name' => 'Delete Post', 'slug' => 'delete-post', 'group_name' => 'post'],
            ['name' => 'Publish Post', 'slug' => 'publish-post', 'group_name' => 'post'],
            ['name' => 'Create Category', 'slug' => 'create-category', 'group_name' => 'category'],
            ['name' => 'Edit Category', 'slug' => 'edit-category', 'group_name' => 'category'],
            ['name' => 'Delete Category', 'slug' => 'delete-category', 'group_name' => 'category'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'group_name' => 'user'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group_name' => 'user'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'group_name' => 'user'],
            ['name' => 'Manage Comments', 'slug' => 'manage-comments', 'group_name' => 'comment'],
            ['name' => 'Manage Contacts', 'slug' => 'manage-contacts', 'group_name' => 'contact'],
        ];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['slug' => $p['slug']], array_merge($p, ['status' => 'active']));
        }

        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $adminRole->permissions()->sync(Permission::pluck('id'));
        }
    }
}
