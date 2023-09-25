<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = config("users");
        foreach ($users as $user) {
            $new_user = new User();
            $user["password"] = bcrypt($user["password"]);
            $new_user->fill($user);
            $new_user->save();
        }
    }
}
