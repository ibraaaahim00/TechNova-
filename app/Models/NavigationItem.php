<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    use HasTranslations;

    protected $fillable = ['label', 'url', 'is_visible', 'sort_order', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['label'];
    }

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'translations' => 'array'];
    }
}
