<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = ['project_category_id', 'title', 'slug', 'summary', 'description', 'client_name', 'completed_at', 'project_url', 'github_url', 'image_path', 'gallery', 'seo_title', 'seo_description', 'is_published', 'is_featured', 'is_concept', 'sort_order', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['title', 'summary', 'description', 'client_name', 'seo_title', 'seo_description'];
    }

    protected function casts(): array
    {
        return ['gallery' => 'array', 'completed_at' => 'date', 'is_published' => 'boolean', 'is_featured' => 'boolean', 'is_concept' => 'boolean', 'translations' => 'array'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }
}
