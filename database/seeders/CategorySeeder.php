<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert([[
            'sex_id' => 2,
            'name' => 'Open', 
            'description' => 'Categoria Open Masculina',
            'year1' => 1920,
            'year2' => 2007
        ], [
            'sex_id' => 1,
            'name' => 'Open', 
            'description' => 'Categoria Open Femenino',
            'year1' => 1920,
            'year2' => 2007
        ], [
            'sex_id' => 2,
            'name' => 'Cadete', 
            'description' => 'Categoria Cadete Masculina',
            'year1' => 2005,
            'year2' => 2007
            
        ], [
            'sex_id' => 1,
            'name' => 'Cadete', 
            'description' => 'Categoria Cadete Femenino',
            'year1' => 2005,
            'year2' => 2007
        ], [
            'sex_id' => 2,
            'name' => 'Junior', 
            'description' => 'Categoria Junior Masculina',
            'year1' => 2003,
            'year2' => 2004
        ], [
            'sex_id' => 1,
            'name' => 'Junior', 
            'description' => 'Categoria Junior Femenino',
            'year1' => 2003,
            'year2' => 2004
        ], [
            'sex_id' => 2,
            'name' => 'Senior', 
            'description' => 'Categoria Senior Masculina',
            'year1' => 1982,
            'year2' => 2002
        ], [
            'sex_id' => 1,
            'name' => 'Senior', 
            'description' => 'Categoria Senior Femenino',
            'year1' => 1982,
            'year2' => 2002
        ], [
            'sex_id' => 2,
            'name' => 'Master', 
            'description' => 'Categoria Master Masculina',
            'year1' => 1972,
            'year2' => 1981
        ], [
            'sex_id' => 1,
            'name' => 'Master', 
            'description' => 'Categoria Master Femenino',
            'year1' => 1972,
            'year2' => 1981
        ], [
            'sex_id' => 2,
            'name' => 'GRANDMaster', 
            'description' => 'Categoria GRANDMaster Masculina',
            'year1' => 1962,
            'year2' => 1971
        ], [
            'sex_id' => 1,
            'name' => 'GRANDMaster', 
            'description' => 'Categoria GRANDMaster Femenino',
            'year1' => 1962,
            'year2' => 1971
        ], [
            'sex_id' => 2,
            'name' => 'GRANDGRANDMaster', 
            'description' => 'Categoria GRANDGRANDMaster Masculina',
            'year1' => 1920,
            'year2' => 1961
        ], [
            'sex_id' => 1,
            'name' => 'GRANDGRANDMaster', 
            'description' => 'Categoria GRANDGRANDMaster Femenino',
            'year1' => 1920,
            'year2' => 1961
        ]]);
    }
}
