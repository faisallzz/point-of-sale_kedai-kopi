<?php

namespace Database\Factories;

use App\Models\BahanBaku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BahanBaku>
 */
class BahanBakuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_bahan_baku' => fake()->randomElement([
                'Biji Kopi Arabika', 'Biji Kopi Robusta', 'Susu Segar UHT', 
                'Sirup Vanila', 'Sirup Karamel', 'Bubuk Cokelat', 'Gula Aren'
            ]),
            'stok' => fake()->randomFloat(2, 10, 100),
            'satuan' => fake()->randomElement(['gram', 'ml', 'pcs']),
        ];
    }
}
