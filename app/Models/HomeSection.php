<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['key', 'eyebrow', 'title', 'body', 'image_path', 'primary_label', 'primary_url', 'secondary_label', 'secondary_url', 'is_visible', 'sort_order', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['eyebrow', 'title', 'body', 'primary_label', 'secondary_label'];
    }

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'translations' => 'array'];
    }
}
