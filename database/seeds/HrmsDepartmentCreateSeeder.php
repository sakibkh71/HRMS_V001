<?php

use Illuminate\Database\Seeder;
use App\Models\Department;

class HrmsDepartmentCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Department::create([
    		'department_name' => 'HR',
    		'created_by' => 1,
    		'updated_by' => 1
		]);
    }
}
