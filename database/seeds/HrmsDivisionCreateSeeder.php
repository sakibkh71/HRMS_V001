<?php

use Illuminate\Database\Seeder;

class HrmsDivisionCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert([
    		['division_name' => 'Barisal', 'division_bn_name'=>'বরিশাল'],
    		['division_name' => 'Chittagong', 'division_bn_name'=>'চট্টগ্রাম'],
            ['division_name' => 'Dhaka', 'division_bn_name'=>'ঢাকা'],
            ['division_name' => 'Khulna', 'division_bn_name'=>'খুলনা'],
            ['division_name' => 'Rajshahi', 'division_bn_name'=>'রাজশাহী'],
            ['division_name' => 'Rangpur', 'division_bn_name'=>'রংপুর'],
    		['division_name' => 'Sylhet', 'division_bn_name'=>'সিলেট'],
    	]);
    }
}
