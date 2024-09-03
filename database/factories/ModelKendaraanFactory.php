<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModelKendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'MICRO/MINIBUS',
                'SEDAN',
                'BUS',
                'JEEP L.C.HDTP',
                'AMBULANCE',
                'SPD. MOTOR',
                'PICK UP',
                'TRUCK',
                'BLIND/DEL.VAN',
                'LIGHT TRUCK'
            ]),
        ];
    }
}
