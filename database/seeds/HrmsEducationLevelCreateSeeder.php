<?php

use Illuminate\Database\Seeder;

class HrmsEducationLevelCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('education_levels')->insert([
    		['education_level_name' => 'Board'],
    		['education_level_name' => 'University'],
		]);
    }
}
