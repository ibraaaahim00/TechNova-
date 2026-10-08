<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasTranslations;

    protected $fillable = ['person_name', 'person_role', 'company', 'quote', 'photo_path', 'is_approved', 'sort_order', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['person_name', 'person_role', 'company', 'quote'];
    }

    protected function casts(): array
    {
        return ['is_approved' => 'boolean', 'translations' => 'array'];
    }
}
