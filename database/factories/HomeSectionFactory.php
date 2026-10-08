<?php

namespace Database\Factories;

use App\Models\HomeSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeSection>
 */
class HomeSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(),
            'eyebrow' => fake()->words(2, true),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'is_visible' => true,
            'sort_order' => 0,
        ];
    }
}
