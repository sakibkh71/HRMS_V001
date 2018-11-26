<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Department::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'department_name' => $faker->name,
        'status' => 1,
        'created_by' => 1,
    ];
});


$factory->define(App\Models\Attendance::class,function(Faker\Generator $faker){
	return [
		'user_id' => 1,
		'in_time' => $faker->time('H:i'),
		'out_time' => $faker->time('H:i'),
		'total_work_hour' => $faker->randomDigit,
		'date' => date('Y-m-d'),
	];
});



$factory->define(App\Models\AttendanceTimesheet::class,function(Faker\Generator $faker){
	return [
		'user_id' => 1,
		'date' => date('Y-m-d'),
		'observation' => 1,
		'in_time' => $faker->time('H:i'),
		'out_time' => $faker->time('H:i'),
		'leave_type' => $faker->word,
	];
});
