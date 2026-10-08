<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = ['blog_category_id', 'user_id', 'title', 'slug', 'excerpt', 'body', 'cover_path', 'seo_title', 'seo_description', 'published_at', 'is_published', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['title', 'excerpt', 'body', 'seo_title', 'seo_description'];
    }

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_published' => 'boolean', 'translations' => 'array'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
