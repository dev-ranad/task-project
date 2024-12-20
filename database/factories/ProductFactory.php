<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'sku' => strtoupper(Str::random(10)),
            'amazon' => $this->faker->numberBetween(10000000, 99999999),
            'ebay' => $this->faker->numberBetween(10000000, 99999999),
            'product_type' => $this->faker->randomElement(['Electronics', 'Clothing', 'Home', 'Beauty', 'Books']),
            'quantity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(100, 10000),
        ];
    }
}
