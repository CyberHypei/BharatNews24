<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Full access', 'status' => 'active'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Can edit and publish content', 'status' => 'active'],
            ['name' => 'Author', 'slug' => 'author', 'description' => 'Can create and edit own posts', 'status' => 'active'],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Registered user', 'status' => 'active'],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], $r);
        }
    }
}
