<?php

namespace Modules\MusicPlayer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\MusicPlayer\Models\Song::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'artist' => $this->faker->name(),
            'title' => $this->faker->sentence(3),
            'image' => "https://picsum.photos/300/300?random=" . rand(1,999),
        ];
    }
}

