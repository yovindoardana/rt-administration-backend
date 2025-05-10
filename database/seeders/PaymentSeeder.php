<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $residentIds = Resident::pluck('id')->toArray();
        $houseIds = House::pluck('id')->toArray();

        // Buat 100 payment acak selama setahun terakhir
        foreach (range(1, 100) as $index) {
            $date = $faker->dateTimeBetween('-1 year', 'now');

            Payment::create([
                'resident_id' => $faker->randomElement($residentIds),
                'house_id' => $faker->randomElement($houseIds),  // <<< tambahkan ini
                'fee_type' => $faker->randomElement(['security', 'cleaning']),
                'month' => $date->format('m'),
                'year' => $date->format('Y'),
                'duration_months' => $faker->numberBetween(1, 12),
                'amount' => $faker->numberBetween(100_000, 5_000_000),
                'status' => $faker->randomElement(['paid', 'unpaid']),
                'payment_date' => $date->format('Y-m-d'),
            ]);
        }
    }
}
