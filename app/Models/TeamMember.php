<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'role', 'bio', 'photo_path', 'skills', 'social_links', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['skills' => 'array', 'social_links' => 'array', 'is_active' => 'boolean'];
    }
}
