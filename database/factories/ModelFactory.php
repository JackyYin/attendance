<?php

use Illuminate\Support\Facades\Hash;

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

$factory->define(App\Models\Company::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('zh_TW');
    return [
        'tax_id_number' => $faker->VAT,
    ];
});

$factory->afterCreating(App\Models\Company::class, function ($company, $faker) {
    $company->profile()->create([
        'name' => $faker->company
    ]);

    $department = $company->departments()->create([
        'name' => $company->profile->name
    ]);

    $department->roles()->createMany([
        [
            'name' => '主管',
            'priority' => 1
        ],
        [
            'name' => '管理員',
            'priority' => 2
        ],
        [
            'name' => '員工',
            'priority' => 3
        ],
    ]);
});

$factory->define(App\Models\Agent::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'auth_hook_url' => $faker->url
    ];
});

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    $company = factory(App\Models\Company::class)->create();

    return [
        'company_id' => $company->id,
        'department_id' => $company->departments()->first()->id,
        'email' => $faker->email,
        'password' => Hash::make(12345678)
    ];
});

$factory->afterCreating(App\Models\User::class, function ($user, $faker) {
    $user->profile()->create([
        'name' => $faker->name,
        'staff_code' => $faker->randomNumber,
        'phone_number' => $faker->tollFreePhoneNumber,
        'on_board_date' => $faker->unixTime($max = 'now')
    ]);

    $agent = factory(App\Models\Agent::class)->create();

    $user->agents()->attach($agent, [
        'email_auth_token' => Hash::make($faker->word)
    ]);

    $role = $user->department->roles()->first();

    $user->roles()->attach($role);
});

