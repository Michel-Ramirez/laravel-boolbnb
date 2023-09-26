<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\House;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([UserSeeder::class, AddressSeeder::class, SponsorSeeder::class, ServiceSeeder::class, HouseSeeder::class,  MessageSeeder::class,   ViewSeeder::class]);
    }
}
