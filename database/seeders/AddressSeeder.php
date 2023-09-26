<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $addresses = config("addresses");
        foreach ($addresses as $address) {
            $new_address = new Address();
            $new_address->fill($address);
            $new_address->save();
        }
    }
}
