<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $services = config("homeServices");
        foreach ($services as $service) {
            $new_service = new Service();
            $new_service->fill($service);
            $new_service->save();
        }
    }
}
