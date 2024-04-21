<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\LawyersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LawyersSeeder::class,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Hev Abi',
            'email' => 'hance.abubo27@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
