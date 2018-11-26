<?php

use Illuminate\Database\Seeder;

class HrmsBasicSalaryInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('basic_salary_info')->insert([
    		['salary_info_name' => 'House Rent', 'salary_info_amount' => '30', 'salary_info_amount_status' => 0, 'salary_info_type'=>'allowance'],
    		['salary_info_name' => 'Food Allowance', 'salary_info_amount' => '20', 'salary_info_amount_status' => 0, 'salary_info_type'=>'deduction'],
            ['salary_info_name' => 'Transport', 'salary_info_amount' => '10', 'salary_info_amount_status' => 0, 'salary_info_type'=>'allowance'],
            ['salary_info_name' => 'Mobile Allowance', 'salary_info_amount' => '5', 'salary_info_amount_status' => 0, 'salary_info_type'=>'allowance'],
            ['salary_info_name' => 'Provident Fund', 'salary_info_amount' => '2', 'salary_info_amount_status' => 0, 'salary_info_type'=>'allowance'],
            ['salary_info_name' => 'Tax', 'salary_info_amount' => '23', 'salary_info_amount_status' => 0, 'salary_info_type'=>'deduction']
    	]);
    }
}
