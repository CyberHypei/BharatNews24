<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Rules\SecureFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function createAnonymousPost()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('frontend.post-anonymous', compact('categories'));
    }

    public function storeAnonymousPost(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', new SecureFileUpload(), 'max:2048'],
            'is_anonymous' => ['nullable', 'boolean'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'author_mobile' => ['nullable', 'numeric', 'digits_between:10,15'],
        ];

        $isAnonymous = $request->boolean('is_anonymous');
        if (! $isAnonymous) {
            $rules['author_name'] = ['required', 'string', 'max:255'];
            $rules['author_email'] = ['required', 'email:rfc,dns', 'max:255'];
        }

        $validated = $request->validate($rules);

        $author = User::orderBy('id')->first();
        if (! $author) {
            return back()->withInput()->with('error', 'Unable to submit. Please try again later.');
        }

        $validated['user_id'] = $author->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = 'draft';
        $validated['is_featured'] = false;
        $validated['is_anonymous'] = $isAnonymous;
        if (! $isAnonymous) {
            $validated['author_name'] = $validated['author_name'] ?? null;
            $validated['author_email'] = $validated['author_email'] ?? null;
            $validated['author_mobile'] = $validated['author_mobile'] ?? null;
        } else {
            $validated['author_name'] = null;
            $validated['author_email'] = null;
            $validated['author_mobile'] = null;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $dir = public_path('uploads/posts');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $name = Str::random(20) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($dir, $name);
            $validated['image'] = 'uploads/posts/' . $name;
        }

        Post::create($validated);

        return redirect()->route('post.anonymous.create')->with('success', 'Your post has been submitted and is pending review. It will be published after approval.');
    }

    public function show(string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'user', 'postImages', 'approvedComments.user'])
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        $categories = Category::where('status', 'active')->withCount('publishedPosts')->orderBy('name')->get();

        return view('frontend.post', compact('post', 'relatedPosts', 'categories'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['post_id'] = $post->id;
        $validated['status'] = 'pending';

        Comment::create($validated);

        return back()->with('success', 'Your comment has been submitted and is awaiting approval.');
    }
}
