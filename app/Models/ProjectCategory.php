<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['name'];
    }

    protected function casts(): array
    {
        return ['translations' => 'array'];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
