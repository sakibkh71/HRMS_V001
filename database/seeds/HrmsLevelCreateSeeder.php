<?php

use Illuminate\Database\Seeder;
use App\Models\Level;

class HrmsLevelCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Level::create([
    		'level_name' => 'Super Admin',
    		'description' => 'This is demo level for super admin user.',
    		'created_by' => 1,
    		'updated_by' => 1
		]);
    }
}
