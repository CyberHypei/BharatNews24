<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'World', 'slug' => 'world', 'description' => 'World news', 'status' => 'active'],
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech news', 'status' => 'active'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports news', 'status' => 'active'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business news', 'status' => 'active'],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Entertainment', 'status' => 'active'],
        ];
        foreach ($categories as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }
    }
}
