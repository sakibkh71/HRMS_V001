<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class HrmsUserCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facker = Faker\Factory::create();

        User::create([
            'employee_no' => '0000-0000',
            'designation_id' => '1',
            'employee_type_id' => '1',
            'first_name' => 'AFC',
            'last_name' => 'Health',
            'email' => 'afc@gmail.com',
            'password' => bcrypt('123456'),
            'mobile_number' => "0123456789",
        ]);
    }
}
