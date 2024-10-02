<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'role_id' => 1,
        //     'password' => bcrypt('password'),
        // ]);

        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'username' => 'admin123',
                'role_id' => 1, // Pastikan role_id sesuai dengan ID yang ada di tabel roles
                'password' => Hash::make('admin#123'), // Ganti dengan password yang sesuai
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Guest',
                'email' => 'guest@gmail.com',
                'role_id' => 2, // Pastikan role_id sesuai dengan ID yang ada di tabel roles
                'password' => Hash::make('123456'), // Ganti dengan password yang sesuai
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
