<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'user_id', 'title', 'slug', 'image', 'short_description',
        'content', 'tags', 'status', 'is_featured', 'is_anonymous', 'author_name', 'author_email', 'author_mobile',
        'views', 'video', 'meta_title', 'meta_description', 'meta_keywords', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_anonymous' => 'boolean',
        ];
    }

    /** Display name on frontend: "Anonymous" or author name */
    public function getDisplayAuthorNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->author_name ?? $this->user?->name ?? 'Unknown';
    }

    /** Display email on frontend: null when anonymous, else author or user email */
    public function getDisplayAuthorEmailAttribute(): ?string
    {
        if ($this->is_anonymous) {
            return null;
        }
        return $this->author_email ?? $this->user?->email ?? null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }

    public function postImages(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }
}
