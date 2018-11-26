<?php

use Illuminate\Database\Seeder;

class HrmsBloodGroupCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blood_groups')->insert([
        	['blood_name'=>'A+'],
        	['blood_name'=>'A-'],
        	['blood_name'=>'B+'],
        	['blood_name'=>'B-'],
        	['blood_name'=>'O+'],
        	['blood_name'=>'O-'],
        	['blood_name'=>'AB+'],
        	['blood_name'=>'AB-'],
    	]);
    }
}
