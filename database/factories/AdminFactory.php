<?php

$this->factory->define(\Main\Models\Admin::class, function (\Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->email,
        'token' => $faker->password,
        'password' => password_hash($faker->password, PASSWORD_DEFAULT),
    ];
});