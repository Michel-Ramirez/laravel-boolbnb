<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $messages = config("messages");
        foreach ($messages as $message) {
            $new_message = new Message();
            $new_message->house_id = rand(1, 5);
            $new_message->fill($message);
            $new_message->save();
        }
    }
}
