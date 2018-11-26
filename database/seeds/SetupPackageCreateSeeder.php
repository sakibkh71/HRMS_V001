<?php

use Illuminate\Database\Seeder;

class SetupPackageCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('packages')->insert([
            ['package_name'=>'Silver', 'package_details'=>'Silver package details lorem ipsam ', 'package_price'=>'100000', 'package_duration'=>'6', 'package_type'=>'1', 'package_sister_concern_limit'=>'0', 'package_level_limit'=>'5', 'package_user_limit'=>'49', 'package_status'=>'1', 'package_created_by'=>'1'],
            ['package_name'=>'Gold', 'package_details'=>'GOld package details lorem ipsam', 'package_price'=>'180000', 'package_duration'=>'12', 'package_type'=>'1', 'package_sister_concern_limit'=>'0', 'package_level_limit'=>'10', 'package_user_limit'=>'100', 'package_status'=>'1', 'package_created_by'=>'1'],
            ['package_name'=>'Platinum', 'package_details'=>'Platinum package details lorem ipsam', 'package_price'=>'500000', 'package_duration'=>'36', 'package_type'=>'1', 'package_sister_concern_limit'=>'3', 'package_level_limit'=>'100', 'package_user_limit'=>'1000', 'package_status'=>'1', 'package_created_by'=>'1'],
            ['package_name'=>'Diamond', 'package_details'=>'Diamond package details lorem ipsam', 'package_price'=>'700000', 'package_duration'=>'60', 'package_type'=>'1', 'package_sister_concern_limit'=>'10', 'package_level_limit'=>'100', 'package_user_limit'=>'1000', 'package_status'=>'1', 'package_created_by'=>'1']
        ]);
    }
}
