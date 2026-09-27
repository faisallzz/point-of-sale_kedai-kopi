<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_menu' => fake()->randomElement([
                'Espresso Single', 'Americano', 'Caffe Latte', 
                'Cappuccino', 'Caramel Macchiato', 'Mocha Coffee', 
                'Cold Brew', 'Matcha Latte', 'V60 Arabica'
            ]),
            'harga_jual' => fake()->randomFloat(2, 18000, 45000),
        ];
    }
}
