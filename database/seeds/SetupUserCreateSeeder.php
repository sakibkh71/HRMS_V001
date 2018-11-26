<?php

use Illuminate\Database\Seeder;
use App\Models\Setup\User;

class SetupUserCreateSeeder extends Seeder
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
            'first_name' => 'IDDL',
            'last_name' => 'Ltd',
            'email' => 'iddl@gmail.com',
            'password' => bcrypt('iddl123'),
            'user_type' => '1',
            'mobile_number' => "01783605360",
        ]);
    }
}
