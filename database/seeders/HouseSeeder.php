<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houses = config("houses");
        foreach ($houses as $house) {
            $new_house = new House();
            $new_house->user_id = rand(1, 3);
            $new_house->fill($house);
            $new_house->save();
        }
    }
}
