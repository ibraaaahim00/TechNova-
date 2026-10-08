<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['title', 'slug', 'eyebrow', 'intro', 'image_path', 'seo_title', 'seo_description', 'is_published', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['title', 'eyebrow', 'intro', 'seo_title', 'seo_description'];
    }

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'translations' => 'array'];
    }
}
