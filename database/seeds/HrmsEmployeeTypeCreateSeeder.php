<?php

use Illuminate\Database\Seeder;

class HrmsEmployeeTypeCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_types')->insert([
            ['type_name' => 'Permanent'],
            ['type_name' => 'Trainee/Probation'],
            ['type_name' => 'Part time'],
            ['type_name' => 'Special Contract'],
        	]);
    }
}
