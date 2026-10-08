<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'icon', 'summary', 'description', 'features', 'image_path', 'seo_title', 'seo_description', 'is_published', 'is_featured', 'sort_order'];

    protected function casts(): array
    {
        return ['features' => 'array', 'is_published' => 'boolean', 'is_featured' => 'boolean'];
    }
}
