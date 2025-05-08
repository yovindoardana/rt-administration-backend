<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        House::factory()->create([
            'house_number' => 'House 1',
            'status' => 'vacant',
        ]);

        House::factory()->create([
            'house_number' => 'House 2',
            'status' => 'occupied',
        ]);
        House::factory()->create([
            'house_number' => 'House 3',
            'status' => 'vacant',
        ]);
        House::factory()->create([
            'house_number' => 'House 4',
            'status' => 'occupied',
        ]);
        House::factory()->create([
            'house_number' => 'House 5',
            'status' => 'vacant',
        ]);
        House::factory()->create([
            'house_number' => 'House 6',
            'status' => 'occupied',
        ]);
        House::factory()->create([
            'house_number' => 'House 7',
            'status' => 'vacant',
        ]);
        House::factory()->create([
            'house_number' => 'House 8',
            'status' => 'occupied',
        ]);
        House::factory()->create([
            'house_number' => 'House 9',
            'status' => 'vacant',
        ]);
        House::factory()->create([
            'house_number' => 'House 10',
            'status' => 'occupied',
        ]);
    }
}
