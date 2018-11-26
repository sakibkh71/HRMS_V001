<?php

use Illuminate\Database\Seeder;

class HrmsReligionsCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religions')->insert([
			['religion_name'=>'Muslims'],
			['religion_name'=>'Christians'],
			['religion_name'=>'Buddhists'],
			['religion_name'=>'Hindus'],
			['religion_name'=>'Jews'],
			['religion_name'=>'Sikhs'],
			['religion_name'=>'Jainism'],
			['religion_name'=>'No-religious'],
			['religion_name'=>'Others'],
    	]);
    }
}
