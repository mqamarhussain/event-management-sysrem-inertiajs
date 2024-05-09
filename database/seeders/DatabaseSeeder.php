<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => 'password',
        ]);
        User::factory()->create([
            'first_name' => 'Promoter',
            'last_name' => 'Promoter',
            'email' => 'promoter@mail.com',
            'password' => 'password',
        ]);

        User::factory(5)->create();
        $this->call(RolesSeeder::class);
        $this->call(VenueSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(AttendeeRequestSeeder::class);
    }
}
