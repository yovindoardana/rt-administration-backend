<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ResidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Buat 50 resident dengan data faker
        foreach (range(1, 50) as $index) {
            Resident::create([
                'full_name'        => $faker->name,
                'residency_status' => $faker->randomElement(['permanent', 'contract']),
                'phone_number'     => $faker->phoneNumber,
                'marital_status'   => $faker->randomElement(['married', 'single']),
            ]);
        }
    }
}
