<?php

$this->factory->define(\Main\Models\User::class, function (\Faker\Generator $faker) {
        return [
            'username' => $faker->userName,
            'email'    => $faker->email,
            'token'    => $faker->password,
            'password' => password_hash($faker->password, PASSWORD_DEFAULT),
            'moto'	   	=> $faker->moto,
            'address'	=> $faker->address,
            'mission'	=> $faker->mission,
            'vision'	=> $faker->vision,
            'about'		=> $faker->about,
            'phone'		=> $faker->phone,
            'search_terms'=> $faker->search_terms,
            'image' => 'https://static.productionready.io/images/smiley-cyrus.jpg',
        ];
    });