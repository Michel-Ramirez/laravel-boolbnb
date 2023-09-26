<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Service;
use App\Models\Sponsor;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prendo gli id dei servizzi
        $service_ids = Service::pluck("id")->toArray();
        // Prendo gli id dei sponsor
        $sponsor_ids = Sponsor::pluck("id")->toArray();

        $houses = config("houses");
        foreach ($houses as $house) {
            $new_house = new House();
            $new_house->user_id = rand(1, 3);
            $new_house->address_id = rand(1, 5);
            $new_house->fill($house);
            $new_house->save();

            // Aggiungiamo la relazione many to many con i servizzi
            $house_service = [];
            foreach ($service_ids as $s) {
                if (rand(0, 1)) $house_service[] = $s;
            }
            $new_house->services()->attach($house_service);

            // Aggiungiamo la relazione many to many con gli sponsor
            $random_index = array_rand($sponsor_ids);
            $random_value = $sponsor_ids[$random_index];
            $sponsor = Sponsor::find($random_value);
            $start_sponsor = new DateTime();
            $end = new DateTime();
            $end_sponsor = $end->add(new DateInterval("PT" . $sponsor->duration . "H"));
            $new_sponsorization = [
                [
                    "sponsor_id" => $random_value,
                    "sponsor_start" => $start_sponsor,
                    "sponsor_end" => $end_sponsor
                ]
            ];
            $new_house->sponsors()->attach($new_sponsorization);
        }
    }
}
