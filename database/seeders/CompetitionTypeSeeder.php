<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('competition_types')->insert([[
            'name' => 'Cto. Euskadi',
            'description' => 'If the Cup Ranking scores with more points'
        ], [
            'name' => 'Cto.Bizkaia and Cto. Guipúzcoa',
            'description' => 'does not score a cup'
        ], [
            'name' => 'Cup',
            'description' => 'If you score Cup Ranking'
        ]]);
    }
}
