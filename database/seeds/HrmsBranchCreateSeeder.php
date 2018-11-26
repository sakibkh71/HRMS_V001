<?php

use App\Models\Branch;
use Illuminate\Database\Seeder;

class HrmsBranchCreateSeeder extends Seeder
{     
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'branch_name' => 'Dhaka',
            'branch_email' => 'info@iddl.com',
            'branch_mobile' => '01832308565',
            'branch_phone' => '01832308565',
            'branch_location' => 'Niketon,Dhaka.',
            'branch_status' => '1',
        ]);
    }
}
