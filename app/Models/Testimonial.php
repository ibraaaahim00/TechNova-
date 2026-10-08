<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['person_name', 'person_role', 'company', 'quote', 'photo_path', 'is_approved', 'sort_order'];

    protected function casts(): array
    {
        return ['is_approved' => 'boolean'];
    }
}
