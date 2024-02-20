<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            //field name unique faker name 
            

            'name' =>  fake()->unique()->name(),

            'image' => '/images/articles/no_article.png' ,
            'description' => fake()->text(200),
           
            'user_id' => fake()->numberBetween(1, 100),
            'category_id' => fake()->numberBetween(1, 100),
           
        ];
    }
}
