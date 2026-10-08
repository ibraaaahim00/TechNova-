<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'eyebrow', 'intro', 'image_path', 'seo_title', 'seo_description', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
