<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Jon Deo',
            'slug' => 'jon-deo',
            'sub_category_id' => rand(25, 26),
            'title' => 'Risus massa tristique neque ad',
            'color' => 'white',
            'price' => rand(10000, 20000),
            'discount' => rand(1000, 5000),
            'offer' => 'Hot',
            'des' => fake()->paragraph(),
            'image' => fake()->image(),
        ];
    }
}
