<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasTranslations;

    protected $fillable = ['key', 'value', 'translations'];

    protected function translatableAttributes(): array
    {
        return ['value'];
    }

    protected function casts(): array
    {
        return ['translations' => 'array'];
    }
}
