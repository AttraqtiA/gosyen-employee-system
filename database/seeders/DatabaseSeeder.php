<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Octa',
            'email' => 'octa@gmail.com',
            'role' => 2,
        ]);

        User::factory()->create([
            'name' => 'Andi',
            'email' => 'andi@gmail.com',
            'role' => 3,
        ]);
    }
}
