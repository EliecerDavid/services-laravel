<?php

use Faker\Generator as Faker;

$factory->define(App\Employee::class, function (Faker $faker) {
    return [
        'person_id' => function () {
            return factory(\App\Person::class)->create()->id;
        },
    ];
});
