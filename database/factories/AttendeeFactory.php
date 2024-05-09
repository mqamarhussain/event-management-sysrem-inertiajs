<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendee;
use App\Models\Event;

class AttendeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get a random event
        $event = Event::inRandomOrder()->first();

        return [
            'event_id' => $event->id,
            'customer_id' => \App\Models\User::factory(), // Assuming you have customers with IDs from 1 to 100
            'content' => $this->faker->paragraph,
            'payment_status' => $this->faker->randomElement([0, 1]), // Randomly choose between 0 (pending) and 1 (paid)
            'amount_paid' => $event->amount, // Use the event's amount as the amount paid
            'status' => $this->faker->randomElement([0, 1, 2]), // Randomly choose between 0, 1, 2 for status
        ];
    }
}
