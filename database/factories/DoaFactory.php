<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        return [
            'judul' => $this->faker->sentence(),
            'gambar' => $this->faker->imageUrl(),
            'keterangan' => $this->faker->paragraph(),
            'riwayat' => $this->faker->optional()->paragraph(),
            'untuk_pribadi' => $user->is_admin ? false : $this->faker->boolean(),
            'user_id' => $user->id,
        ];
    }
}
