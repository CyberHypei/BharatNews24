<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $categories = Category::all();
        if ($user && $categories->isNotEmpty()) {
            $titles = [
                'Breaking: Latest developments in technology sector',
                'Sports roundup: Highlights from the weekend',
                'Business trends to watch this year',
                'Entertainment industry updates',
                'World leaders meet for summit',
            ];
            foreach ($titles as $i => $title) {
                Post::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($title)],
                    [
                        'category_id' => $categories->random()->id,
                        'user_id' => $user->id,
                        'title' => $title,
                        'short_description' => 'Short summary of this news article for listing pages.',
                        'content' => "Full content of the article. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris.",
                        'tags' => 'news, update',
                        'status' => 'published',
                        'is_featured' => $i < 2,
                        'views' => rand(10, 500),
                        'published_at' => now()->subDays(rand(1, 30)),
                    ]
                );
            }
        }
    }
}
