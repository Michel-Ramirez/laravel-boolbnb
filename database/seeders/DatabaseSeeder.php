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
        $this->call([UserSeeder::class, AddressSeeder::class, HouseSeeder::class,  MessageSeeder::class, ServiceSeeder::class, SponsorSeeder::class, ViewSeeder::class]);
    }
}
