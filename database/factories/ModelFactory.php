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
            'company_id' => $department->company->id,
            'name' => '主管',
            'priority' => 1
        ],
        [
            'company_id' => $department->company->id,
            'name' => '管理員',
            'priority' => 2
        ],
        [
            'company_id' => $department->company->id,
            'name' => '員工',
            'priority' => 3
        ],
    ]);

    $department->locations()->createMany([
        [
            'company_id' => $department->company->id,
            'name' => '家樂福桂林店',
            'latitude' => '25.038854',
            'longitude' => '121.505592',
            'address' => '108台北市萬華區桂林路1號',
            'legal_distance' => 200
        ],
        [
            'company_id' => $department->company->id,
            'name' => '家樂福便利購台北濟南店Carrefour Market Taipei Jinan Store',
            'latitude' => '25.041514',
            'longitude' => '121.531015',
            'address' => '100台北市中正區濟南路二段46號',
            'legal_distance' => 300
        ],
        [
            'company_id' => $department->company->id,
            'name' => '家樂福重慶店Carrefour Chung Qing Store',
            'latitude' => '25.061844',
            'longitude' => '121.514236',
            'address' => '103台北市大同區重慶北路二段171號',
            'legal_distance' => 100
        ],
    ]);
});

$factory->define(App\Models\Agent::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
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

    \App\Models\Agent::insert([
        [
            'name' => 'App',
            'auth_hook_url' => $faker->url
        ],
        [
            'name' => 'Line',
            'auth_hook_url' => $faker->url
        ],
        [
            'name' => 'Stride',
            'auth_hook_url' => $faker->url
        ],
        [
            'name' => 'Discord',
            'auth_hook_url' => $faker->url
        ],
    ]);

    $agents = \App\Models\Agent::inRandomOrder()->get();

    foreach ($agents as $agent) {
        $user->agents()->attach($agent, [
            'email_auth_token' => Hash::make($faker->word)
        ]);
    }

    $role = $user->department->roles()->first();

    $user->roles()->attach($role);
});

