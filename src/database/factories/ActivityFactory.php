<?php

namespace Database\Factories;

use App\Utils\ImageUtils;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition()
    {
        $images = ImageUtils::downloadImage(256, 256);
        if ($images !== null) {
            $image = $images['original'];
            $thumbnail = $images['thumbnail'];
        } else {
            $image = 'default.png';
            $thumbnail = 'default_thumb.png';
        }

        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(),
            'image' => $images['original'],
            'thumbnail' => $images['thumbnail']
        ];
    }
}
