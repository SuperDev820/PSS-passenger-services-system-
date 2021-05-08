<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('modalities')->insert([[
            'name' => 'Short Boat'
        ], [
            'name' => 'Long Ship'
        ]]);
    }
}
