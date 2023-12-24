<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Employee::class, function (Faker $faker) {
    static $employeeId = 10000;
    return [        
        'employee_id' => ++$employeeId,
        'emp_name' => $faker->name,
        'password' => Hash::make('password')            
    ];
});
