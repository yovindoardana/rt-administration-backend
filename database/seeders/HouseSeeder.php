<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Buat 20 rumah dengan nomor acak dan status occupied/vacant
        foreach (range(1, 20) as $index) {
            House::create([
                'house_number' => strtoupper($faker->bothify('??-###')),
                'status'       => $faker->randomElement(['occupied', 'vacant']),
            ]);
        }
    }
}
