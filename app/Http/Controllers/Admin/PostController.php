<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Category;
use App\Rules\SecureFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $posts = $query->latest()->paginate(15)->withQueryString();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'category_id' => ['required', 'exists:categories,id'],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['nullable', 'boolean'],
            'is_anonymous' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', new SecureFileUpload(), 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:102400'],
            'images.*' => ['nullable', 'image', new SecureFileUpload(), 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['user_id'] = $request->user()->id;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_anonymous'] = $request->boolean('is_anonymous');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadFile($request->file('image'), 'posts');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $this->uploadFile($request->file('video'), 'posts/videos');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        unset($validated['images']);
        $post = Post::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file->isValid()) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image' => $this->uploadFile($file, 'post_images'),
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $post->load('postImages');
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {   
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'category_id' => ['required', 'exists:categories,id'],
            'short_description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['nullable', 'boolean'],
            'is_anonymous' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', new SecureFileUpload(), 'max:2048'],
            'video' => ['nullable', 'file', 'mimes:mp4,webm,ogg', 'max:102400'],
            'images.*' => ['nullable', 'image', new SecureFileUpload(), 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_anonymous'] = $request->boolean('is_anonymous');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadFile($request->file('image'), 'posts');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $this->uploadFile($request->file('video'), 'posts/videos');
        }

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        unset($validated['images']);
        $post->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file->isValid()) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image' => $this->uploadFile($file, 'post_images'),
                        'sort_order' => $post->postImages()->max('sort_order') + 1 + $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function deletePostImage(PostImage $postImage)
    {
        $postImage->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Image removed.']);
        }
        return back()->with('success', 'Image removed.');
    }

    private function uploadFile($file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'uploads/' . $folder . '/' . $name;
    }
}
