<?php

use App\Models\Units;
use Illuminate\Database\Seeder;

class HrmsUnitCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Units::create([
        	'unit_name' => 'HR Unit',
        	'unit_parent_id' => '0',
        	'unit_departments_id' => '1',
        	'unit_details' => 'This is demo Unit for HR',
        	]);
    }
}
