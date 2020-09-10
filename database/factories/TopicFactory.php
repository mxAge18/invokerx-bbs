<?php
use App\Models\User;
use Faker\Generator as Faker;


$factory->define(App\Models\Topic::class, function (Faker $faker) {

    $sentence = $faker->sentence();

    $updated_at = $faker->dateTimeThisMonth();

    // 为创建时间传参，意为最大不超过 $updated_at，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        // 'name' => $faker->name,
        'title' => $sentence,
        'body' => $faker->text(),
        'excerpt' => $sentence,
        'user_id' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
        'category_id' => $faker->randomElement([1, 2, 3, 4]),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
