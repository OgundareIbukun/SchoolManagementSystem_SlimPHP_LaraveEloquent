<?php

use Main\Models\Article;
use Main\Models\Comment;
use Main\Models\User;

$this->factory->define(Comment::class, function (\Faker\Generator $faker) {
        return [
            'body'        => $faker->sentences(rand(1,5), true),
            'article_id'   => function () {
                return $this->factory->of(Article::class)->create()->id;
            },
            'user_id'   => function () {
                return $this->factory->of(User::class)->create()->id;
            },
        ];
    });
