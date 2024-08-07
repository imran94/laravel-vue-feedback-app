<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackPostFactory extends Factory
{
    private static $categories = [
        'UI',
        'UX',
        'Enhancement',
        'Bug',
        'Feature'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(100),
            'description' => fake()->paragraphs(3, true),
            'category' => self::$categories[array_rand(self::$categories)]
        ];
    }
}
