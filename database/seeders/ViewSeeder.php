<?php

namespace Database\Seeders;

use App\Models\View;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $views = config("homeViews");
        foreach ($views as $view) {
            $new_view = new View();
            $new_view->house_id = rand(1, 5);
            $new_view->fill($view);
            $new_view->save();
        }
    }
}
