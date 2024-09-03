<?php

namespace Database\Seeders;

use App\Models\ModelKendaraan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ModelKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ModelKendaraan::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'MICRO/MINIBUS',
            'SEDAN',
            'BUS',
            'JEEP L.C.HDTP',
            'AMBULANCE',
            'SPD. MOTOR',
            'PICK UP',
            'TRUCK',
            'BLIND/DEL.VAN',
            'LIGHT TRUCK',
        ];

        foreach ($data as $key => $value) {
            ModelKendaraan::insert([
                'name' => $value
            ]);
        }
    }
}
