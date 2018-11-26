<?php

use Illuminate\Database\Seeder;
use App\Models\Designation;

class HrmsDesignationCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Designation::create([
    		'department_id' => 1,
    		'level_id' => 1,
    		'designation_name' => 'HR Manager',
    		'designation_description' => 'This is demo designation for HR Manager Level.',
    		'created_by' => 1,
    		'updated_by' => 1
		]);
    }
}
