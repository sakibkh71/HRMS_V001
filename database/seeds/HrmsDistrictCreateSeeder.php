<?php

use Illuminate\Database\Seeder;

class HrmsDistrictCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->insert([
            ['division_id'=>'3', 'district_name'=>'Dhaka', 'district_bn_name'=>'ঢাকা', 'district_lat'=>'23.7115253', 'district_lon'=>'90.4111451', 'district_website'=>'www.dhaka.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Faridpur', 'district_bn_name'=>'ফরিদপুর', 'district_lat'=>'23.6070822', 'district_lon'=>'89.8429406', 'district_website'=>'www.faridpur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Gazipur', 'district_bn_name'=>'গাজীপুর', 'district_lat'=>'24.0022858', 'district_lon'=>'90.4264283', 'district_website'=>'www.gazipur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Gopalganj', 'district_bn_name'=>'গোপালগঞ্জ', 'district_lat'=>'23.0050857', 'district_lon'=>'89.8266059', 'district_website'=>'www.gopalganj.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Jamalpur', 'district_bn_name'=>'জামালপুর', 'district_lat'=>'24.937533', 'district_lon'=>'89.937775', 'district_website'=>'www.jamalpur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Kishoreganj', 'district_bn_name'=>'কিশোরগঞ্জ', 'district_lat'=>'24.444937', 'district_lon'=>'90.776575', 'district_website'=>'www.kishoreganj.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Madaripur', 'district_bn_name'=>'মাদারীপুর', 'district_lat'=>'23.164102', 'district_lon'=>'90.1896805', 'district_website'=>'www.madaripur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Manikganj', 'district_bn_name'=>'মানিকগঞ্জ', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.manikganj.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Munshiganj', 'district_bn_name'=>'মুন্সিগঞ্জ', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.munshiganj.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Mymensingh', 'district_bn_name'=>'ময়মনসিং', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.mymensingh.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Narayanganj', 'district_bn_name'=>'নারায়াণগঞ্জ', 'district_lat'=>'23.63366', 'district_lon'=>'90.496482', 'district_website'=>'www.narayanganj.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Narsingdi', 'district_bn_name'=>'নরসিংদী', 'district_lat'=>'23.932233', 'district_lon'=>'90.71541', 'district_website'=>'www.narsingdi.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Netrokona', 'district_bn_name'=>'নেত্রকোনা', 'district_lat'=>'24.870955', 'district_lon'=>'90.727887', 'district_website'=>'www.netrokona.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Rajbari', 'district_bn_name'=>'রাজবাড়ি', 'district_lat'=>'23.7574305', 'district_lon'=>'89.6444665', 'district_website'=>'www.rajbari.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Shariatpur', 'district_bn_name'=>'শরীয়তপুর', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.shariatpur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Sherpur', 'district_bn_name'=>'শেরপুর', 'district_lat'=>'25.0204933', 'district_lon'=>'90.0152966', 'district_website'=>'www.sherpur.gov.bd'],
            ['division_id'=>'3', 'district_name'=>'Tangail', 'district_bn_name'=>'টাঙ্গাইল', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.tangail.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Bogra', 'district_bn_name'=>'বগুড়া', 'district_lat'=>'24.8465228', 'district_lon'=>'89.377755', 'district_website'=>'www.bogra.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Joypurhat', 'district_bn_name'=>'জয়পুরহাট', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.joypurhat.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Naogaon', 'district_bn_name'=>'নওগাঁ', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.naogaon.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Natore', 'district_bn_name'=>'নাটোর', 'district_lat'=>'24.420556', 'district_lon'=>'89.000282', 'district_website'=>'www.natore.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Nawabganj', 'district_bn_name'=>'নবাবগঞ্জ', 'district_lat'=>'24.5965034', 'district_lon'=>'88.2775122', 'district_website'=>'www.chapainawabganj.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Pabna', 'district_bn_name'=>'পাবনা', 'district_lat'=>'23.998524', 'district_lon'=>'89.233645', 'district_website'=>'www.pabna.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Rajshahi', 'district_bn_name'=>'রাজশাহী', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.rajshahi.gov.bd'],
            ['division_id'=>'5', 'district_name'=>'Sirajgonj', 'district_bn_name'=>'সিরাজগঞ্জ', 'district_lat'=>'24.4533978', 'district_lon'=>'89.7006815', 'district_website'=>'www.sirajganj.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Dinajpur', 'district_bn_name'=>'দিনাজপুর', 'district_lat'=>'25.6217061', 'district_lon'=>'88.6354504', 'district_website'=>'www.dinajpur.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Gaibandha', 'district_bn_name'=>'গাইবান্ধা', 'district_lat'=>'25.328751', 'district_lon'=>'89.528088', 'district_website'=>'www.gaibandha.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Kurigram', 'district_bn_name'=>'কুড়িগ্রাম', 'district_lat'=>'25.805445', 'district_lon'=>'89.636174', 'district_website'=>'www.kurigram.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Lalmonirhat', 'district_bn_name'=>'লালমনিরহাট', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.lalmonirhat.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Nilphamari', 'district_bn_name'=>'নীলফামারী', 'district_lat'=>'25.931794', 'district_lon'=>'88.856006', 'district_website'=>'www.nilphamari.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Panchagarh', 'district_bn_name'=>'পঞ্চগড়', 'district_lat'=>'26.3411', 'district_lon'=>'88.5541606', 'district_website'=>'www.panchagarh.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Rangpur', 'district_bn_name'=>'রংপুর', 'district_lat'=>'25.7558096', 'district_lon'=>'89.244462', 'district_website'=>'www.rangpur.gov.bd'],
            ['division_id'=>'6', 'district_name'=>'Thakurgaon', 'district_bn_name'=>'ঠাকুরগাঁও', 'district_lat'=>'26.0336945', 'district_lon'=>'88.4616834', 'district_website'=>'www.thakurgaon.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Barguna', 'district_bn_name'=>'বরগুনা', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.barguna.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Barisal', 'district_bn_name'=>'বরিশাল', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.barisal.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Bhola', 'district_bn_name'=>'ভোলা', 'district_lat'=>'22.685923', 'district_lon'=>'90.648179', 'district_website'=>'www.bhola.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Jhalokati', 'district_bn_name'=>'ঝালকাঠি', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.jhalakathi.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Patuakhali', 'district_bn_name'=>'পটুয়াখালী', 'district_lat'=>'22.3596316', 'district_lon'=>'90.3298712', 'district_website'=>'www.patuakhali.gov.bd'],
            ['division_id'=>'1', 'district_name'=>'Pirojpur', 'district_bn_name'=>'পিরোজপুর', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.pirojpur.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Bandarban', 'district_bn_name'=>'বান্দরবান', 'district_lat'=>'22.1953275', 'district_lon'=>'92.2183773', 'district_website'=>'www.bandarban.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Brahmanbaria', 'district_bn_name'=>'ব্রাহ্মণবাড়িয়া', 'district_lat'=>'23.9570904', 'district_lon'=>'91.1119286', 'district_website'=>'www.brahmanbaria.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Chandpur', 'district_bn_name'=>'চাঁদপুর', 'district_lat'=>'23.2332585', 'district_lon'=>'90.6712912', 'district_website'=>'www.chandpur.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Chittagong', 'district_bn_name'=>'চট্টগ্রাম', 'district_lat'=>'22.335109', 'district_lon'=>'91.834073', 'district_website'=>'www.chittagong.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Comilla', 'district_bn_name'=>'কুমিল্লা', 'district_lat'=>'23.4682747', 'district_lon'=>'91.1788135', 'district_website'=>'www.comilla.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Coxs Bazar', 'district_bn_name'=>'কক্স বাজার', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.coxsbazar.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Feni', 'district_bn_name'=>'ফেনী', 'district_lat'=>'23.023231', 'district_lon'=>'91.3840844', 'district_website'=>'www.feni.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Khagrachari', 'district_bn_name'=>'খাগড়াছড়ি', 'district_lat'=>'23.119285', 'district_lon'=>'91.984663', 'district_website'=>'www.khagrachhari.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Lakshmipur', 'district_bn_name'=>'লক্ষ্মীপুর', 'district_lat'=>'22.942477', 'district_lon'=>'90.841184', 'district_website'=>'www.lakshmipur.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Noakhali', 'district_bn_name'=>'নোয়াখালী', 'district_lat'=>'22.869563', 'district_lon'=>'91.099398', 'district_website'=>'www.noakhali.gov.bd'],
            ['division_id'=>'2', 'district_name'=>'Rangamati', 'district_bn_name'=>'রাঙ্গামাটি', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.rangamati.gov.bd'],
            ['division_id'=>'7', 'district_name'=>'Habiganj', 'district_bn_name'=>'হবিগঞ্জ', 'district_lat'=>'24.374945', 'district_lon'=>'91.41553', 'district_website'=>'www.habiganj.gov.bd'],
            ['division_id'=>'7', 'district_name'=>'Maulvibazar', 'district_bn_name'=>'মৌলভীবাজার', 'district_lat'=>'24.482934', 'district_lon'=>'91.777417', 'district_website'=>'www.moulvibazar.gov.bd'],
            ['division_id'=>'7', 'district_name'=>'Sunamganj', 'district_bn_name'=>'সুনামগঞ্জ', 'district_lat'=>'25.0658042', 'district_lon'=>'91.3950115', 'district_website'=>'www.sunamganj.gov.bd'],
            ['division_id'=>'7', 'district_name'=>'Sylhet', 'district_bn_name'=>'সিলেট', 'district_lat'=>'24.8897956', 'district_lon'=>'91.8697894', 'district_website'=>'www.sylhet.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Bagerhat', 'district_bn_name'=>'বাগেরহাট', 'district_lat'=>'22.651568', 'district_lon'=>'89.785938', 'district_website'=>'www.bagerhat.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Chuadanga', 'district_bn_name'=>'চুয়াডাঙ্গা', 'district_lat'=>'23.6401961', 'district_lon'=>'88.841841', 'district_website'=>'www.chuadanga.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Jessore', 'district_bn_name'=>'যশোর', 'district_lat'=>'23.16643', 'district_lon'=>'89.2081126', 'district_website'=>'www.jessore.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Jhenaidah', 'district_bn_name'=>'ঝিনাইদহ', 'district_lat'=>'23.5448176', 'district_lon'=>'89.1539213', 'district_website'=>'www.jhenaidah.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Khulna', 'district_bn_name'=>'খুলনা', 'district_lat'=>'22.815774', 'district_lon'=>'89.568679', 'district_website'=>'www.khulna.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Kushtia', 'district_bn_name'=>'কুষ্টিয়া', 'district_lat'=>'23.901258', 'district_lon'=>'89.120482', 'district_website'=>'www.kushtia.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Magura', 'district_bn_name'=>'মাগুরা', 'district_lat'=>'23.487337', 'district_lon'=>'89.419956', 'district_website'=>'www.magura.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Meherpur', 'district_bn_name'=>'মেহেরপুর', 'district_lat'=>'23.762213', 'district_lon'=>'88.631821', 'district_website'=>'www.meherpur.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Narail', 'district_bn_name'=>'নড়াইল', 'district_lat'=>'23.172534', 'district_lon'=>'89.512672', 'district_website'=>'www.narail.gov.bd'],
            ['division_id'=>'4', 'district_name'=>'Satkhira', 'district_bn_name'=>'সাতক্ষীরা', 'district_lat'=>'0', 'district_lon'=>'0', 'district_website'=>'www.satkhira.gov.bd'],
        ]);
    }
}
