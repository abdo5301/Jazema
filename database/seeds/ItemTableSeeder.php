<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $name_ar = $faker->name;
        $name_en = $faker->name;
        DB::table('items')->insert([
            'item_category_id' => rand(1,20),
            'item_type_id' => rand(1,20),
            'user_id' => rand(1,10),
            'name_ar' => $name_ar,
            'name_en' => $name_en,
            'description_ar' => str_random(50),
            'description_en' => str_random(50),
            'creatable_id' => 1,
            'creatable_type' => 'App\Models\Staff',
            'price' => rand(1000,99999),
            'quantity' => rand(1000,99999),
            'views' => rand(1000,99999),
            'like' => rand(1000,99999),
            'comments' => rand(1000,99999),
            'share' => rand(1000,99999),
            'deals' => rand(1000,99999),
            'rank' => rand(1,5),
            'status' => 'active',
            'slug_ar' => str_slug($name_ar),
            'slug_en' => str_slug($name_en),

        ]);
        //
    }
}
