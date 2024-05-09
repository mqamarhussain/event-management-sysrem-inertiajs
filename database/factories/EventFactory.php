<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_id' => \App\Models\Venue::factory(),
            'event' => $this->faker->sentence,
            'promoter_id' => \App\Models\User::factory(),
            'description' => $this->faker->paragraph,
            'event_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'type' => $this->faker->randomElement(['1', '2']),
            'audience_capacity' => $this->faker->numberBetween(50, 200),
            'payment_type' => $this->faker->randomElement(['1', '2']),
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'banner' => $this->faker->imageUrl(640, 480, 'events'),
        ];
    }
}
