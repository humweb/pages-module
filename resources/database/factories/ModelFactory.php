<?php


$factory->define(\Humweb\Pages\Models\Page::class, function (Faker\Generator $faker) {
    $title = $faker->text(25);

    return [
        'title'            => $title,
        'content'          => $faker->text,
        'parent_id'        => 0,
        'layout'           => '',
        'slug'             => str_slug($title),
        'uri'              => str_slug($title),
        'created_by'       => 1,
        'css'              => '',
        'js'               => '',
        'published'        => 1,
        'meta_title'       => $title,
        'meta_description' => $faker->text,
        'meta_robots'      => '',
        'is_index'         => false,
    ];
});
