<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'role', 'bio', 'photo_path', 'skills', 'social_links', 'is_active', 'sort_order', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['name', 'role', 'bio', 'skills'];
    }

    protected function casts(): array
    {
        return ['skills' => 'array', 'social_links' => 'array', 'is_active' => 'boolean', 'translations' => 'array'];
    }
}
