<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesFactory extends Factory
{
    protected $model = Categories::class;

    public function definition(): array
    {
    	return [
        'title' => $this->faker->sentence,
        'icon'  =>$this->faker->paragraph
    	];
    }
}
